<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MobileAuthController extends BaseController
{
    /**
     * =======================
     * Mobile Login API
     * =======================
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors()->all(),
                'Validation failed',
                422
            );
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return $this->invalidLogin();
        }

        if ($user->status !== 'active') {
            return $this->errorResponse(
                ['Account is not active'],
                'Account is not active, contact administrator',
                401
            );
        }

        if ($user->block_until && Carbon::parse($user->block_until)->isFuture()) {
            return $this->errorResponse(
                ['Account blocked'],
                'Account blocked until ' . $user->block_until->toDateTimeString(),
                401
            );
        }

        if (!Auth::guard('web')->attempt($request->only('username', 'password'))) {
            return $this->handleFailedLogin($user);
        }

        $user->update([
            'login_attempts' => 0,
            'block_until'    => null
        ]);

        $user->tokens()->delete();

        $token = $user->createToken('mobile-app')->plainTextToken;

        $user->load([
            'staffs.branch.company',
            'role'
        ]);

        $staff = $user->staffs;

        $staff->role = $user->role->name ?? null;
        $staff->status = $user->status;

        return $this->successResponse([
            'token'  => $token,
            'user'   => $user->only('id', 'username', 'status'),
            'staffs' => $staff,
            'parking_location' => $user->parkingLocation
        ], 'Login successful');

    }

    /**
     * =======================
     * Failed Login Handler
     * =======================
     */
    private function handleFailedLogin(User $user)
    {
        $user->increment('login_attempts');

        if ($user->login_attempts >= 3) {
            $blockTime = Carbon::now()->addMinutes(15);

            $user->update([
                'block_until'    => $blockTime,
                'login_attempts' => 0
            ]);

            return $this->errorResponse(
                ['Too many attempts'],
                'Account blocked until ' . $blockTime->toDateTimeString(),
                401
            );
        }

        return $this->errorResponse(
            ['Invalid credentials'],
            'Invalid username or password',
            401
        );
    }

    private function invalidLogin()
    {
        return $this->errorResponse(
            ['Invalid credentials'],
            'Invalid username or password',
            401
        );
    }

    /**
     * =======================
     * Mobile Logout
     * =======================
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse([], 'Logged out successfully');
    }

    /**
     * =======================
     * User Profile
     * =======================
     */
    public function profile(Request $request)
    {
        $request->user()->load('staffs.branch.company');

        return $this->successResponse(
            $request->user(),
            'User profile'
        );
    }

    /**
     * =======================
     * USER LOCATION + RATES
     * =======================
     */
    public function userLocation(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->staffs) {
            return $this->errorResponse([], 'User or staff not found', 404);
        }

        $staff  = $user->staffs;
        $branch = $staff->branch;
        $location = $user->parkingLocation;

        if (!$branch) {
            return $this->errorResponse([], 'Branch not found', 404);
        }

        $municipal = $branch->municipal ?? null;

        $rates = VehicleType::query()
            ->get()
            ->mapWithKeys(function ($vehicle) {
                return [
                    'id' => $vehicle->vehicle_type_id,
                    'name' => strtolower($vehicle->type_name),
                    'rate' => (float) $vehicle->hourly_rate
                ];
            });

        return $this->successResponse([
            'location_id'   => $location?->location_id ?? 0,
            'location_name' => $location?->location_name ?? '',
            'municipal_id'  => $location?->municipal_id ?? 0,
            'rates'         => $rates
        ], 'User location and rates retrieved successfully');
    }
}
