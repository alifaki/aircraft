<?php

namespace App\Http\Controllers;

use App\Models\ApplicationPayment;
use App\Models\Bill;
use App\Models\Branch;
use App\Models\BankAccount;
use App\Models\Logger;
use App\Services\AzampayService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BillController extends BaseController
{
    protected $billUrl = '';
    protected $billToken = '';
    protected AzampayService $azampay;

    function __construct(AzampayService $azampay)
    {
        $this->billToken = config('app.samis.billToken');
        $this->billUrl = config('app.samis.billUrl');
        $this->azampay = $azampay;
    }
    public function index()
    {
        $query = Bill::with(['branch', 'branch.company', 'items', 'bankAccount', 'payments']);

        // Apply filters if they exist
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        if (request()->has('branch_id')) {
            $query->where('branch_id', request('branch_id'));
        }
        if (request()->has('bank_account_id')) {
            $query->where('bank_account_id', request('bank_account_id'));
        }

        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('control_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        if (request()->has('createdRange')) {
            $dates = explode(' - ', request('createdRange'));

            if (count($dates) === 2) {
                try {
                    $fromDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                    $toDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                    $query->whereBetween('created_at', [
                        $fromDate,
                        $toDate
                    ]);
                } catch (\Exception $e) {
                    // Handle invalid date format if needed
                }
            }
        }

        if (request()->has('expiryRange')) {
            $dates = explode(' - ', request('expiryRange'));

            if (count($dates) === 2) {
                try {
                    $fromDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                    $toDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();

                    $query->whereBetween('expires_at', [
                        $fromDate,
                        $toDate
                    ]);
                } catch (\Exception $e) {
                    // Handle invalid date format if needed
                }
            }
        }

        if (request()->has('expiry_from') && request()->has('expiry_to')) {
            $query->whereBetween('expires_at', [
                request('expiry_from'),
                request('expiry_to')
            ]);
        }

        // Check for expired bills and update their status
        $this->checkExpiredBills();

        $bills = $query->orderByDesc("updated_at")->get();
        return $this->successResponse($bills, 'Bills retrieved successfully');
    }

    public function store(Request $request)
    {
        $request->merge([
            'call_back_url' => config('app.samis.billCallBackUrl') ?? null,
            'bill_reference' => 'BILL-' . strtoupper(uniqid()),
            'generated_by' => Auth::user() ? "STF-".Auth::user()->id.":".Auth::user()->staffs->first_name." ".Auth::user()->staffs->last_name : 'System',
            'approved_by'  => Auth::user() ? "STF-".Auth::user()->id.":".Auth::user()->staffs->first_name." ".Auth::user()->staffs->last_name : 'System',
            'branch_id'    => Auth::user()->staffs->branch_id ?? $request->branch_id,
            'bill_option'  => $request->payment_option
        ]);
        $request->request->remove('payment_option');

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'control_number' => 'nullable|string|max:50',
            'call_back_url' => 'nullable|string|max:255',
            'customer_id' => 'required|string|max:50',
            'bill_option' => 'integer|in:1,2,3', // 1. full, 2. partial, 3. exactly
            'customer_type' => 'string|max:100|in:student,parent,teacher,staff,others,employee,guest,customer,admission',
            'description' => 'required|string|max:200',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'numeric|min:0',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:15',
            'customer_email' => 'required|email|max:50',
            'generated_by' => 'required|string|max:255',
            'approved_by' => 'required|string|max:255',
            'expires_at' => 'required|date',
            'cancellation_reason' => 'nullable|string|max:200',
            'currency' => 'string|max:50',
            'status' => 'string|max:50|in:pending,paid,cancelled,expired',
            'items' => 'required|array|min:1',
            'items.*.student_bill_id' => 'nullable|numeric|exists:student_bills,id',
            'items.*.item_reference' => 'required|string|max:100',
            'items.*.payment_reference' => 'required|string|max:50',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.gfs_code' => 'required|exists:bill_structures,gfs_code',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $bankAccount = BankAccount::find($request->bank_account_id);
            $requestData = $request->all();

            // Generate control_number for local control accounts only
            if ($bankAccount->is_localControl) {
                $ispNumber = $bankAccount->isp_number;
                $uniqueNumber = str_pad(mt_rand(1, 9999999), 8, '0', STR_PAD_LEFT);
                $requestData['control_number'] = $ispNumber . $uniqueNumber;
            } else {
                $requestData['control_number'] = null;
            }

            $bill = Bill::create($requestData);

            foreach ($request->items as $item) {
                $bill->items()->create($item);
            }

            // Reload the bill with all relationships for gateway payload
            $freshBill = Bill::with(['branch', 'bankAccount', 'items.parameter'])->find($bill->id);

            // Always send to payment gateway endpoint
            $azamResponse = [];
            if ($request->has('useAzamPay') && $request->useAzamPay) {
                $azamResponse = $this->azampay->mobileCheckout([
                    'phone'       => $freshBill->customer_phone,
                    'amount'      => $freshBill->amount,
                    'external_id' => $freshBill->control_number,
                ]);

                // If AzamPay fails, don't commit the bill, roll back before exit
                if (!$azamResponse['success']) {
                    throw new \Exception($azamResponse['message'] ?? 'Mobile payment failed');
                }
            }

            // Send to SAMIS; if it fails, do not commit the bill, roll back before exit
            $gatewayResponse = $this->sendTosamis($freshBill);
            if (!$gatewayResponse['success']) {
                throw new \Exception($gatewayResponse['message'] ?? 'Failed to send bill to samis');
            }

            // Success: now commit
            DB::commit();
            return $this->successResponse($freshBill, 'Bill created successfully', 201);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage() ?? 'Bill creation failed');
        }
    }
    public function azamPayMobileCheckout(Request $request)
    {
        $request->merge([
            'phone'       => $request->phone,
            'amount'      => $request->amount,
            'external_id' => $request->controlNumber,
            'provider'    => 'Mpesa',
        ]);
        $validator = Validator::make($request->all(), [
            'phone'       => 'required|string|max:15',
            'amount'      => 'required|numeric|min:0',
            'external_id' => 'required|string|max:50',
            'provider'    => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Validation error', 422);
        }

        // 1. Send mobile checkout request to AzamPay
        $response = $this->azampay->mobileCheckout($request->all());

        // 2. Check if the response from AzamPay was successful
        if (!isset($response['success']) || !$response['success']) {
            return $this->errorResponse($response['message'] ?? 'Mobile checkout failed', 500);
        }

        // 3. Retrieve transactionId and save it as bill_reference (or billRefrence?) to the bill
        $transactionId = $response['transactionId'] ?? null;
        if ($transactionId) {
            $bill = \App\Models\Bill::where('control_number', $request->controlNumber)->first();
            if ($bill) {
                $bill->bill_reference = $transactionId;
                $bill->save();
            }
        }
        
        // 4. Wait until the bill status changes from 'pending' to 'paid' (or fails), check with a timeout loop
        // The callback from AzamPay will update the bill status, so we loop + sleep for some time
        $waitSeconds = 30; // total seconds to wait
        $interval = 2; // seconds between each check
        $elapsed = 0;
        $finalStatus = null;

        // refetch the bill each time
        while ($elapsed < $waitSeconds) {
            sleep($interval);
            $elapsed += $interval;
            $bill = \App\Models\Bill::where('control_number', $request->controlNumber)->first();

            // If the bill status is still pending, keep waiting
            if ($bill && strtolower($bill->status) !== 'pending') {
                $finalStatus = $bill->status;
                break;
            }
        }

        // 5. Return response to customer
        if ($finalStatus === 'paid') {
            return $this->successResponse([
                'message' => 'Mobile checkout successful and bill paid',
                'transactionId' => $bill->bill_reference,
                'bill_status' => $finalStatus,
            ]);
        } else if ($finalStatus && $finalStatus !== 'pending') {
            return $this->errorResponse('Bill status: ' . $finalStatus, 'Mobile checkout failed', 500);
        } else {
            return $this->errorResponse(
                'Payment is still pending confirmation after waiting ' . $waitSeconds . ' seconds.',
                'Mobile checkout pending',
                202
            );
        }
    }
    public function azamPayCallback(Request $request)
    {
        $data = $request->all();
        // Log raw callback
        $signature = $request->header('x-api-key');

        // Find Bill by control_number
        $bill = Bill::where('control_number', $data['utilityref'])->first();

        if (!$bill) {
            return $this->errorResponse('Bill not found', 'Bill not found', 404);
        }

        try {
            DB::beginTransaction();

            $status = strtolower($data['transactionstatus']) === 'success' ? 'paid' : 'failed';
            $paidAmount = (float)($data['amount'] ?? 0);

            // Insert into PaymentFlow
            $receiptNumber = "RCPT-" . strtoupper(uniqid());
            $billRefrence  = $data['reference'] ?? $bill->bill_reference;

            $isRefrenceExist = DB::table("payment_flows")->where(["billRefrence"   => $billRefrence])->exists();
            if ($isRefrenceExist) {
                return $this->errorResponse('Reference already exists', 'Reference already exists', 422);
            }
            DB::table("payment_flows")->insert([
                "bill_id"        => $bill->id,
                "paidAmount"     => $paidAmount,
                "serviceProvider"=> $data['operator'] ?? 'AzamPay',
                "receiptNumber"  => $receiptNumber,
                "billRefrence"   => $billRefrence,
                "PyrName"        => $bill->customer_name,
                "payerPhone"     => $data['msisdn'] ?? $bill->customer_phone,
                "CtrAccNum"      => $bill->control_number,
                "remark"         => "AzamPay Callback",
                "paidAt"         => now(),
                "created_at"     => now(),
                "updated_at"     => now(),
            ]);

            // Update bill
            $totalPaid = DB::table("payment_flows")
                ->where("bill_id", $bill->id)
                ->sum("paidAmount");

            $billStatus = match (true) {
                $status === 'failed'         => 'failed',
                $totalPaid == 0              => 'failed',
                $totalPaid < $bill->amount   => 'partial-paid',
                default                      => 'paid',
            };

            $bill->update([
                "paid_amount" => $totalPaid,
                "status"      => $billStatus,
            ]);

            log::info('Azampay callback response', ['data' => $data, 'signature' => $signature]);
            DB::commit();

            return $this->successResponse(null, 'Callback processed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            log::info('Azampay callback error', ['error' => $e->getMessage()]);
            return $this->errorResponse($e->getMessage(), 'Processing failed', 500);
        }
    }
    protected function sendTosamis(Bill $bill)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $branch = Branch::find($bill->branch_id);
            $bankAccount = BankAccount::find($bill->bank_account_id);

            $payload = [
                "billId" => $bill->id,
                "systemId" => config("app.samis.id"),
                "controlNumber" => $bill->control_number,
                "callBackUrl" => $bill->call_back_url,
                "branchId" => $branch->branch_id ?? '1001',
                "customerId" => $bill->customer_id,
                "billType" => (string)$bill->bill_option,
                "billChanel" => $bankAccount->bank_name ?? 'PBZ',
                "billAmount" => number_format($bill->amount, 2, '.', ''),
                "customerName" => $bill->customer_name,
                "customerPhone" => $bill->customer_phone,
                "billgenerateBy" => $bill->generated_by,
                "customerEmail" => $bill->customer_email,
                "billapprovedBy" => $bill->approved_by,
                "billExpire_at" => Carbon::parse($bill->expires_at)->format('Y-m-d\TH:i:s'),
                "billStatus" => strtolower($bill->status),
                "currency" => $bill->currency ?? 'TZS',
                "billDescription" => $bill->description,
                "billRefrence" => $bill->bill_reference,
                "billCode" => $bill->control_number,
                "PayerName" => $bill->customer_name,
                "billItems" => $bill->items->map(function($item) {
                    return [
                        "billItemId" => $item->id,
                        "billItemRef" => $item->item_reference,
                        "ItemRefonPay" => $item->payment_reference,
                        "itemAmount" => number_format($item->amount, 2, '.', ''),
                        "GfsCode" => $item->gfs_code,
                        "billParameter" => [
                            "p_name" => $item->parameter->name
                        ]
                    ];
                })->toArray()
            ];

            $response = $client->post($this->billUrl.'bill-information', [
                'json' => $payload,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->billToken,
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Log successful request/response
            Logger::create([
                'level'   => 'info',
                'message' => 'Bill sent to SAMIS',
                'context' => [
                    'payload'  => $payload,
                    'response' => $responseData,
                ],
            ]);

            if ($response->getStatusCode() !== 200 || !($responseData['success'] ?? false)) {
                throw new \Exception($responseData['message'] ?? 'Unknown error from gateway');
            }

            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Bill sent to gateway successfully'
            ];

        } catch (\Exception $e) {
            // Log failure
            Logger::create([
                'level'   => 'error',
                'message' => 'Error sending bill to SAMIS',
                'context' => [
                    'bill_id' => $bill->id,
                    'error'   => $e->getMessage(),
                ],
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function show($id)
    {
        $bill = Bill::with(['branch', 'branch.company', 'items', 'bankAccount'])->find($id);

        if (is_null($bill)) {
            return $this->errorResponse('Bill not found', 'Not found', 404);
        }

        return $this->successResponse($bill, 'Bill retrieved successfully');
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Validation error', 422);
        }

        $bill = Bill::find($id);

        if (!$bill) {
            return $this->errorResponse('Bill not found', 'Not found', 404);
        }

        if ($bill->status === 'paid') {
            return $this->errorResponse('Paid bills cannot be cancelled', 'Bad request', 400);
        }

        try {
            DB::beginTransaction();

            // Update the bill status locally
            $bill->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason,
                'updated_at' => now()
            ]);

            // Send cancellation to JKU endpoint
            $gatewayResponse = $this->sendCancel($bill);

            if (!$gatewayResponse['success']) {
                throw new \Exception('Failed to cancel bill in gateway: ' . $gatewayResponse['message']);
            }

            DB::commit();

            return $this->successResponse($bill, 'Bill cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 'Bill cancellation failed', 500);
        }
    }

    protected function sendCancel(Bill $bill)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->put($this->billUrl."bill-information/cancel-bill/".$bill->id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->billToken,
                ],
                'json' => [
                    'cancelationReason' => $bill->cancellation_reason,
                    'billStatus' => 'canceled'
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($response->getStatusCode() !== 200 || !($responseData['success'] ?? false)) {
                throw new \Exception($responseData['message'] ?? 'Unknown error from gateway');
            }

            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Bill cancelled in gateway successfully'
            ];

        } catch (\Exception $e) {
            \Log::error('Gateway Cancel Bill Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        $bill = Bill::find($id);

        if (!$bill) {
            return $this->errorResponse('Bill not found', 'Not found', 404);
        }

        if ($bill->status === 'paid') {
            return $this->errorResponse('Paid bills cannot be deleted', 'Bad request', 400);
        }

        $bill->delete();

        return $this->successResponse(null, 'Bill deleted successfully');
    }

    protected function checkExpiredBills()
    {
        $expiredBills = Bill::where('expires_at', '<', now())
            ->where('status', 'pending')
            ->get();

        foreach ($expiredBills as $bill) {
            $bill->update([
                'status' => 'expired',
                'updated_at' => now()
            ]);
        }
    }

    public function summary(Request $request)
    {
        $filterQuery = Bill::query();

        // Apply filters except "status", so summary for all statuses is accurate
        if ($request->has('branch_id')) {
            $filterQuery->where('branch_id', $request->branch_id);
        }

        if ($request->has('bank_account_id')) {
            $filterQuery->where('bank_account_id', $request->bank_account_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $filterQuery->where(function($q) use ($search) {
                $q->where('control_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        if ($request->has('createdRange')) {
            $dates = explode(' - ', $request->createdRange);
            if (count($dates) === 2) {
                try {
                    $fromDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                    $toDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                    $filterQuery->whereBetween('created_at', [$fromDate, $toDate]);
                } catch (\Exception $e) {
                    // Handle invalid date format
                }
            }
        }

        // If user sets a global status filter, apply it to ALL summary stats
        if ($request->has('status')) {
            $filterQuery->where('status', $request->status);
            // We are showing summary for one status only
            $totalBills = $filterQuery->count();
            $totalAmount = $filterQuery->sum('amount');

            // For detailed breakdown of statuses, zero all except filtered
            $paidAmount = $request->status === "paid" ? $totalAmount : 0;
            $pendingAmount = $request->status === "pending" ? $totalAmount : 0;
            $overdueAmount = $request->status === "expired" ? $totalAmount : 0;
            $cancelledAmount = $request->status === "cancelled" ? $totalAmount : 0;
        } else {
            // Otherwise, fetch all records matching filters
            $totalBills = $filterQuery->count();
            $totalAmount = $filterQuery->sum('amount');

            // Use clone so we don't mutate main query
            $paidAmount = (clone $filterQuery)->where('status', 'paid')->sum('amount');
            $pendingAmount = (clone $filterQuery)->where('status', 'pending')->sum('amount');
            $overdueAmount = (clone $filterQuery)->where('status', 'expired')->sum('amount');
            $cancelledAmount = (clone $filterQuery)->where('status', 'cancelled')->sum('amount');
        }

        return $this->successResponse([
            'total_bills' => $totalBills,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'overdue_amount' => $overdueAmount,
            'cancelled_amount' => $cancelledAmount
        ], 'Summary retrieved successfully');
    }

    /**
     * Check application payment status
     */
    public function checkApplicationPaymentStatus(Request $request, $admissionRegistrationId)
    {
        try {
            $payment = ApplicationPayment::where('admission_registration_id', $admissionRegistrationId)
                ->with(['bill', 'academicYear', 'intake'])
                ->latest()
                ->first();

            if (!$payment) {
                return $this->errorResponse('Payment not found', 'Not found', 404);
            }

            // Update bill status if expired
            if ($payment->bill && $payment->bill->expires_at < Carbon::now() && $payment->status === 'pending') {
                $payment->bill->update(['status' => 'expired']);
                $payment->update(['status' => 'expired']);
                $payment->refresh();
            }

            // Update payment status from bill if needed
            if ($payment->bill && $payment->bill->status !== $payment->status) {
                $payment->update(['status' => $payment->bill->status]);
            }

            $responseData = [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'control_number' => $payment->bill->control_number ?? null,
                'status' => $payment->status,
                'expires_at' => $payment->bill->expires_at->format('Y-m-d H:i:s') ?? null,
                'paid_amount' => $payment->bill->paid_amount ?? 0,
                'is_paid' => $payment->status === 'paid',
                'payment_date' => $payment->payment_date ? $payment->payment_date->format('Y-m-d H:i:s') : null,
                'bill' => [
                    'id' => $payment->bill->id ?? null,
                    'control_number' => $payment->bill->control_number ?? null,
                    'amount' => $payment->bill->amount ?? null,
                    'paid_amount' => $payment->bill->paid_amount ?? 0,
                    'status' => $payment->bill->status ?? null,
                    'expires_at' => $payment->bill->expires_at->format('Y-m-d H:i:s') ?? null
                ]
            ];

            return $this->successResponse($responseData, 'Payment status retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Payment status check error: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), 'Failed to check payment status', 500);
        }
    }
}
