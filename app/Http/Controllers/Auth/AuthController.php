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
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController {
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
    public function login(Request $request) {
        $credentials = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($credentials->fails()) {
            return $this->errorResponse($credentials->errors()->all(), "Validation failed", 422);
        }

        $user = User::where('username', $request->username)->first();

        // Check if user exists
        if (!$user) {
            return $this->handleFailedLogin($request);
        }

        // Check if account is active
        if ($user->status !== 'active') {
            return $this->errorResponse(
                ['login' => ['Account is not active']],
                "Account is not active, please contact system administrator", 401
            );
        }

        // Check if the user is blocked
        if ($user->block_until && Carbon::parse($user->block_until)->isFuture()) {
            return $this->errorResponse(
                ['login' => ['Account is blocked.']],
                'Your account is temporarily blocked until ' . $user->block_until, 401
            );
        }

        // Attempt login
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            // Reset failed attempts after successful login
            $user->update(['login_attempts' => 0, 'block_until' => null]);
            return $this->successResponse(['redirect' => route($user->hasPermission('aviation.view') ? 'dashboard' : 'aviation.my-roster')], "Successfully logged in");
        }

        // Handle failed login
        return $this->handleFailedLogin($request, $user);
    }

    /**
     * Handles failed login attempts
     */
    private function handleFailedLogin(Request $request, $user = null): JsonResponse
    {
        if ($user) {
            $user->increment('login_attempts');

            if ($user->login_attempts >= 3) {
                $blockTime = Carbon::now()->addMinutes(15);
                $user->update([
                    'block_until' => $blockTime,
                    'login_attempts' => 0 // Reset failed login_attempts after blocking
                ]);

                return $this->errorResponse(
                    ['login' => ['Too many failed attempts. Account blocked for 15 minutes.']],
                    'Your account is temporarily blocked until ' . $blockTime->toDateTimeString(),
                    401
                );
            }
        }

        return $this->errorResponse(
            ['login' => ['Invalid credentials']],
            "The provided credentials do not match our records.", 401
        );
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
