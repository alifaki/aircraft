<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\ApiIntegration\TextMessage;
use App\ApiIntegration\SamisTextMessage;
use App\Mail\ResearchOtpMail;
use Illuminate\Support\Facades\Mail;

class ResearcherController extends BaseController
{
    private TextMessage $textMessage;
    private SamisTextMessage $samisTextMessage;

    public function __construct(TextMessage $textMessage, SamisTextMessage $samisTextMessage)
    {
        $this->textMessage = $textMessage;
        $this->samisTextMessage = $samisTextMessage;
    }

    public function index(Request $request)
    {
        $query = Researcher::query();

        if ($request->has('name')) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('organization')) {
            $query->where('organization', 'like', '%' . $request->organization . '%');
        }

        $researchers = $query->get();

        return $this->successResponse($researchers, 'Researchers retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:200',
            'phone_number' => 'required|string|max:30|unique:researchers',
            'email' => 'required|email|max:100|unique:researchers',
            'organization' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 
                $validator->errors()->first()
            );
        }

        // Generate OTP and expiration time
        $accessOtp = $this->generateOtp();
        $otpExpireAt = Carbon::now()->addMinutes(30); // OTP expires in 30 minutes

        $researcherData = array_merge($validator->validated(), [
            'access_otp' => $accessOtp,
            'otp_expire_at' => $otpExpireAt
        ]);

        $researcher = Researcher::create($researcherData);

        // Send OTP notification
        $this->sendOtpNotification($researcher, $accessOtp);

        return $this->successResponse($researcher, 'Researcher created successfully and OTP sent', 201);
    }

    public function show($id)
    {
        $researcher = Researcher::find($id);

        if (is_null($researcher)) {
            return $this->errorResponse('Researcher not found', 'Not found', 404);
        }

        return $this->successResponse($researcher, 'Researcher retrieved successfully');
    }

    public function update(Request $request, Researcher $researcher)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'string|max:200',
            'phone_number' => 'string|max:30|unique:researchers,phone_number,'.$researcher->id,
            'email' => 'email|max:100|unique:researchers,email,'.$researcher->id,
            'organization' => 'string|max:200',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 
                $validator->errors()->first()
            );
        }

        $researcher->update($validator->validated());

        return $this->successResponse($researcher, 'Researcher updated successfully');
    }

    public function destroy(Researcher $researcher)
    {
        $researcher->delete();
        return $this->successResponse(null, 'Researcher deleted successfully');
    }

    /**
     * Generate new OTP for researcher
     */
    public function generateNewOtp(Researcher $researcher)
    {
        $accessOtp = $this->generateOtp();
        $otpExpireAt = Carbon::now()->addMinutes(30);

        $researcher->update([
            'access_otp' => $accessOtp,
            'otp_expire_at' => $otpExpireAt
        ]);

        // Send new OTP notification
        $this->sendOtpNotification($researcher, $accessOtp);

        return $this->successResponse(
            ['otp' => $accessOtp, 'expires_at' => $otpExpireAt],
            'New OTP generated and sent successfully'
        );
    }

    /**
     * Send OTP to researcher
     */
    public function sendOtp(Request $request)
    {
        // Validate inputs (all optional individually)
        $validator = Validator::make($request->all(), [
            'researcher_id' => 'nullable|exists:researchers,id',
            'otp_email' => 'nullable|email|exists:researchers,email',
            'otp_phone' => 'nullable|string|exists:researchers,phone_number',
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse("Validation failed", $validator->errors()->first());
        }
    
        // Ensure at least one field is provided
        if (empty($request->researcher_id) && empty($request->otp_email) && empty($request->otp_phone)) {
            return $this->errorResponse("Validation failed", "Provide at least one: researcher_id, otp_email, or otp_phone.");
        }
    
        // Find researcher by whichever is provided
        $researcher = null;
    
        if ($request->filled('researcher_id')) {
            $researcher = Researcher::find($request->researcher_id);
        } elseif ($request->filled('otp_email')) {
            $researcher = Researcher::where('email', $request->otp_email)->first();
        } elseif ($request->filled('otp_phone')) {
            $researcher = Researcher::where('phone_number', $request->otp_phone)->first();
        }
    
        if (!$researcher) {
            return $this->errorResponse("Researcher not found", "No researcher found matching the provided details.");
        }
    
        // Generate new OTP if none exists or expired
        if (!$researcher->access_otp || Carbon::now()->greaterThan($researcher->otp_expire_at)) {
            $accessOtp = $this->generateOtp();
            $otpExpireAt = Carbon::now()->addMinutes(30);
    
            $researcher->update([
                'access_otp' => $accessOtp,
                'otp_expire_at' => $otpExpireAt,
            ]);
        } else {
            $accessOtp = $researcher->access_otp;
        }
        
        if (!empty($researcher->phone_number)) {
            $this->sendOtpNotification($researcher, $accessOtp);
        }
    
        return $this->successResponse(null, "OTP sent successfully.");
    }    

    /**
     * Verify researcher OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|exists:researchers,email',
            'phone' => 'nullable|string|exists:researchers,phone_number',
            'access_otp' => 'required|string|max:10'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse("Validation failed", $validator->errors()->first());
        }

        // Ensure at least one identifier is provided
        if (empty($request->researcher_id) && empty($request->email) && empty($request->phone)) {
            return $this->errorResponse("Validation failed", "Provide at least one: researcher_id, email, or phone.");
        }

        // Find researcher
        $researcher = null;

        if ($request->filled('email')) {
            $researcher = Researcher::where('email', $request->email)->first();
        } elseif ($request->filled('phone')) {
            $researcher = Researcher::where('phone_number', $request->phone)->first();
        }

        if (!$researcher) {
            return $this->errorResponse([], "No researcher found with the provided information.");
        }

        // Verify OTP
        if (!$researcher->access_otp || $researcher->access_otp !== $request->access_otp) {
            return $this->errorResponse([], "Invalid OTP");
        }

        if (Carbon::now()->gt($researcher->otp_expire_at)) {
            return $this->errorResponse([], "OTP has expired");
        }

        // Optionally clear OTP after verification
        // $researcher->update(['access_otp' => null, 'otp_expire_at' => null]);

        return $this->successResponse(['researcher' => $researcher], "OTP verified successfully");
    }

    /**
     * Generate a 6-digit OTP
     */
    private function generateOtp()
    {
        return strtoupper(Str::random(6)); // Generates a 6-character alphanumeric OTP
        // Alternatively for numeric OTP: return sprintf("%06d", mt_rand(1, 999999));
    }

    /**
     * Send OTP notification to researcher via email and/or SMS
     */
    private function sendOtpNotification(Researcher $researcher, $otp)
    {
        $message = "Your research portal access OTP is: " . $this->formatOtp($otp) . ". It expires in 30 minutes.";

        // Send email notification
        $this->sendOtpEmail($researcher, $message, $otp);

        // Send SMS notification if phone exists
        $this->sendOtpSms($researcher, $message);
    }

    /**
     * Send OTP via email
     */
    private function sendOtpEmail(Researcher $researcher, $message, $otp)
    {
        try {
            $details = [
                'title' => 'Research Portal Access OTP',
                'body' => $message,
                'otp' => $otp,
                'researcher_name' => $researcher->full_name
            ];

            // Uncomment when you have mail setup
            Mail::to($researcher->email)->send(new ResearchOtpMail($details));
   
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email to researcher: " . $e->getMessage());
        }
    }

    /**
     * Send OTP via SMS
     */
    private function sendOtpSms(Researcher $researcher, $message)
    {
        try {
            if ($researcher->phone_number) {
                $phone = preg_replace('/[\s\[\]\(\)-]/', '', $researcher->phone_number);
                
                // Uncomment when you have SMS service
                // $this->textMessage->sendBeam($phone, $message);
                $this->samisTextMessage->sendTextMessage($phone, $message);
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP SMS to researcher: " . $e->getMessage());
        }
    }

    /**
     * Format OTP for display (e.g., XXXX-XX or XXXXXX)
     */
    private function formatOtp($code)
    {
        if (strlen($code) === 6) {
            return substr($code, 0, 3) . '-' . substr($code, 3, 3);
        }
        return $code;
    }
}