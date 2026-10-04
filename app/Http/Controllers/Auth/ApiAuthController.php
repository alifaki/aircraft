<?php

namespace App\Http\Controllers\Auth;

use App\ApiIntegration\TextMessage;
use App\Mail\SamisMail;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends BaseController {
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all(), "Validation failed", 422);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse(
                "Login failed",
                "The provided credentials are incorrect.",
                401);
        }

        if ($user->status !== 'active') {
            return $this->errorResponse(
                "Login failed",
                "Your account is not active. Please contact the administrator.",
                403);
        }

        if ($user->block_until && Carbon::parse($user->block_until)->isFuture()) {
            return $this->errorResponse(
                "Login failed",
                'Your account is blocked until ' . $user->block_until,
                403);
        }

        $user->update(['attempts' => 0, 'block_until' => null]);
        $token = $user->createToken('YourAppName')->plainTextToken;
        return $this->successResponse(
            [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'staff' => $user->staffs,
                ]
            ],
        );
    }
}
