<?php

namespace App\Http\Controllers\Auth;

use App\ApiIntegration\TextMessage;
use App\Mail\SamisMail;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends BaseController {

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => [
                'required',
                'string',
                'min:8',              // Minimum length
                'confirmed',           // Requires matching password_confirmation field
                'regex:/[A-Z]/',       // At least one uppercase
                'regex:/[a-z]/',       // At least one lowercase
                'regex:/[0-9]/',       // At least one number
                'regex:/[@$!%*#?&]/', // At least one special character
            ],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->all(),
                $validator->errors()->first()
            );
        }

        // Get user ID from session safely
        $userId = session('password_reset_user_id');

        if (!$userId) {
            return $this->errorResponse(
                ['Session expired or invalid request'],
                'Password reset session expired. Please start the process again.',
                401
            );
        }

        // Find and update user
        $user = User::find($userId);

        if (!$user) {
            return $this->errorResponse(
                ['User not found'],
                'User account not found. Please contact support.',
                404
            );
        }

        // Update password and clear reset session
       $user->update([
           'password' => Hash::make($request->newPassword)
       ]);

        // Clear all password reset session data
        session()->forget([
            'password_reset_user_id',
            'password_reset_username'
        ]);

        // Optional: Send password change notification
        // $user->notify(new PasswordChangedNotification());

        return $this->successResponse(
            ['redirect' => route('login')],
            'Password reset successfully. Redirecting to login page...'
        );
    }
}
