<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\PaymentFlow;
use App\Models\Bill;
use App\Models\StudentBill;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PaymentFlowController extends BaseController
{
    public function store(Request $request)
    {
        if ($request->paymentMethod == "manual") {
            $request->merge([
                'paidAt'        => Carbon::now(),
                'receiptNumber' => 'RCPT-' . strtoupper(uniqid())
            ]);
        }

        $validator = Validator::make($request->all(), [
            'bill_id'         => 'required|exists:bills,id',
            'paidAmount'      => 'required|numeric|min:0.01',
            'totalAmountPaid' => 'required|numeric|min:0.01',
            'billStatus'      => 'required|string|in:paid,partial-paid,failed',
            'serviceProvider' => 'required|string|max:200',
            'receiptNumber'   => 'required|string|max:200|unique:payment_flows,receiptNumber',
            'billRefrence'    => 'required|string|max:200|unique:payment_flows,billRefrence',
            'PyrName'         => 'required|string|max:200',
            'payerPhone'      => 'required|string|max:20',
            'CtrAccNum'       => 'required',
            'paidAt'          => 'required|date'
        ]);

        if ($validator->fails()) {
            $errors = Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]);
            return $this->errorResponse($errors, $validator->errors()->first(), 422);
        }

        try {
            DB::beginTransaction();

            // 1. Insert payment flow
            $payInfo = [
                "bill_id"        => $request->bill_id,
                "paidAmount"     => $request->paidAmount,
                "serviceProvider"=> $request->serviceProvider,
                "receiptNumber"  => $request->receiptNumber,
                "billRefrence"   => $request->billRefrence,
                "PyrName"        => $request->PyrName,
                "payerPhone"     => $request->payerPhone,
                "CtrAccNum"      => $request->CtrAccNum,
                "remark"         => $request->CtrAccNum,
                "paidAt"         => $request->paidAt
            ];
            DB::table("payment_flows")->insert($payInfo);

            // 2. Update bill
            $bill = Bill::find($request->bill_id);
            if (!$bill) {
                DB::rollBack();
                return $this->errorResponse([], 'Bill not found', 404);
            }

            $totalPaid = DB::table("payment_flows")
                ->where("bill_id", $bill->id)
                ->sum("paidAmount");

            $status = match (true) {
                $totalPaid == 0             => 'failed',
                $totalPaid < $bill->amount  => 'partial-paid',
                default                     => 'paid',
            };

            $bill->update([
                "paid_amount" => $totalPaid,
                "status"      => $status,
            ]);

            // 3. Update cash account
            $cashAccount = CashAccount::where('branch_id', $bill->branch_id)
                ->where('is_active', true)
                ->where('type','bank')
                ->first();

            if ($cashAccount) {
                $cashAccount->increment('current_balance', $request->paidAmount);
            } else {
                DB::rollBack();
                return $this->errorResponse(
                    'Payment recording failed',
                    'No active cash account found for this branch and payment method.',
                    400
                );
            }

            // 4. Record transaction
            $transactionData = [
                'branch_id'            => $bill->branch_id,
                'user_id'              => auth()->id()??null,
                'type'                 => $bill->customer_type == 'student' ? 'fee' : 'income',
                'cash_account_id'      => $cashAccount->id,
                'amount'               => $request->paidAmount,
                'payment_method'       => $request->serviceProvider,
                'reference_number'     => $request->receiptNumber,
                'description'          => "Payment for Bill #{$bill->id}",
                'transactionable_type' => Bill::class,
                'transactionable_id'   => $bill->id,
            ];
            Transaction::create($transactionData);

            DB::commit();
            return $this->successResponse([], 'Payment recorded successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse([], 'Payment recording failed: ' . $e->getMessage(), 500);
        }
    }
   
    public function show($id)
    {
        try {
            $payment = PaymentFlow::with('bill.branch')->find($id);

            if (!$payment) {
                return $this->errorResponse('Payment not found', 'Not found', 404);
            }

            return $this->successResponse($payment, 'Payment retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve payment', 500);
        }
    }
    public function byBill($billId)
    {
        try {
            $payments = PaymentFlow::where('bill_id', $billId)
                ->with('bill.branch')
                ->get();

            return $this->successResponse($payments, 'Payments for bill retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve bill payments', 500);
        }
    }
    public function getByStudentId($studentId)
    {
        try {
            $payments = Bill::where('customer_id', $studentId)
                ->with('payments')
                ->get();

            return $this->successResponse($payments, 'Payments for student retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve student payments', 500);
        }
    }
}
