<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use App\ApiIntegration\TextMessage;
use App\ApiIntegration\SamisTextMessage;
use App\Mail\SamisMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
 
class ConfirmationCodeController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Confirmation Code Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles sending and verifying confirmation codes
    |
    */

    private TextMessage $textMessage;
    private SamisTextMessage $arifMessage;
    
    public function __construct(TextMessage $textMessage, SamisTextMessage $arifMessage)
    {
        $this->textMessage = $textMessage;
        $this->arifMessage = $arifMessage;
    }

    /**
     * Send confirmation code to user
     */
    public function sendConfirmation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse("Validation failed", $validator->errors()->first());
            }

            $username = $request->username;

            // Find user by username
            $user = User::where('username', $username)->first();
            if (!$user) {
                return $this->errorResponse([], "No account matching your username");
            }

            // Generate confirmation code (expires in 5 minutes)
            $code = $user->generateConfirmationCode('password_reset', 5);

            // Prepare message
            $message = "Your confirmation code to reset password is " . $this->formatCode($code->code);

            // Send email
            $details = [
                'title' => 'SAMIS confirmation code',
                'body' => $message
            ];

            Mail::to($user->staffs->email ?? "info@arif.technology")->send(new SamisMail($details));

            // Send SMS if phone exists
            if ($user->staffs->phone) {
                $phone = preg_replace('/[\s\[\]\(\)-]/', '', $user->staffs->phone);
                //$this->textMessage->sendBeam($phone, $message);
                $this->arifMessage->sendTextMessage($phone, $message);
            }

            // Store username in session for next step
            session(['password_reset_username' => $username]);

            return $this->successResponse(
                ['redirect' => route('verify-confirmation-code')],
                "Confirmation code sent successfully"
            );
        } catch (\Exception $e) {
            return $this->errorResponse([], "An error occurred: failed to send confirmation code ".$e->getMessage());
        }
    }

    /**
     * Resend confirmation code
     */
    public function resendConfirmation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse("Validation failed", $validator->errors()->first());
            }

            $username = $request->username;

            // Find user by username
            $user = User::where('username', $username)->first();
            if (!$user) {
                return $this->errorResponse([], "No account matching your username");
            }

            // Invalidate any existing codes first
            $user->confirmationCodes()
                ->where('type', 'password_reset')
                ->where('used_at', NULL)
                ->where('expires_at', '>', now())
                ->update(['used_at' => now()]);

            // Generate new confirmation code (expires in 5 minutes)
            $code = $user->generateConfirmationCode('password_reset', 5);

            // Prepare message
            $message = "Your new confirmation code to reset password is " . $this->formatCode($code->code);

            // Send email
            $details = [
                'title' => 'SAMIS confirmation code',
                'body' => $message
            ];

            Mail::to($user->staffs->email ?? "info@arif.technology")->send(new SamisMail($details));

            // Send SMS if phone exists
            if ($user->staffs->phone) {
                $phone = preg_replace('/[\s\[\]\(\)-]/', '', $user->staffs->phone);
                $this->arifMessage->sendTextMessage($phone, $message);
            }

            return $this->successResponse(
                [],
                "New confirmation code sent successfully"
            );
        } catch (\Exception $e) {
            return $this->errorResponse([], "An error occurred: failed to resend confirmation code ".$e->getMessage());
        }
    }

    /**
     * Verify confirmation code
     */
    public function confirmCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'confirmationCode' => 'required|string',
                'username' => 'required|string' // Added username to identify user
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all(), $validator->errors()->first());
            }

            // Find user by username
            $user = User::where('username', $request->username)->first();

            if (!$user) {
                return $this->errorResponse($validator->errors()->all(), "User not found");
            }

            // Find valid confirmation code
            $confirmationCode = $user->findValidCode(
                $this->unformatCode($request->confirmationCode),
                'password_reset'
            );

            if (!$confirmationCode) {
                return $this->errorResponse("Code error", "Invalid or expired confirmation code");
            }

            // Mark code as used
            $confirmationCode->markAsUsed();

            // Store user ID in session for password reset
            session(['password_reset_user_id' => $user->id]);
            return $this->successResponse(
                ['redirect' => route('change-password')],
                "Code verified. Redirecting to password reset..."
            );
        } catch (\Exception $e) {
            return $this->errorResponse([], "An error occurred: failed to verify confirmation code");
        }
    }

    /**
     * Format code for display (e.g., SAMIS-1-2-3-4-5)
     */
    private function formatCode(string $code): string
    {
        // If code is 32 chars (from Str::random), take first 5 digits
        if (strlen($code) > 5) {
            $code = substr($code, 0, 6);
        }

        $digits = str_split($code);
        return "SAMIS-" . implode('-', $digits);
    }

    /**
     * Unformat code (remove SAMIS- and hyphens)
     */
    private function unformatCode(string $formattedCode): string
    {
        // Remove "SAMIS-" prefix
        $code = str_replace('SAMIS-', '', $formattedCode);
        // Remove hyphens
        return str_replace('-', '', $code);
    }
}