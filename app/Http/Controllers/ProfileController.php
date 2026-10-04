<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class ProfileController extends BaseController
{
    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $staff = $user->staff;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:staff,email,' . $staff->id,
            'phone' => 'required|string|max:20',
            'bio' => 'nullable|string|max:500',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn($error) => $error[0]),
                $validator->errors()->first()
            );
        }

        try {
            $data = $validator->validated();

            // Handle photo upload
            if ($request->hasFile('photo_path')) {
                // Delete old photo if exists
                if ($staff->photo_path && Storage::exists($staff->photo_path)) {
                    Storage::delete($staff->photo_path);
                }

                $path = $request->file('photo_path')->store('staff-photos', 'public');
                $data['photo_path'] = $path;
            }

            $staff->update($data);

            return $this->successResponse(
                $staff->fresh(),
                'Profile updated successfully'
            );

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Profile update failed', 500);
        }
    }

    /**
     * Update profile photo only
     */
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn($error) => $error[0]),
                $validator->errors()->first()
            );
        }

        try {
            $staff = auth()->user()->staff;

            // Delete old photo if exists
            if ($staff->photo_path && Storage::exists($staff->photo_path)) {
                Storage::delete($staff->photo_path);
            }

            $path = $request->file('photo_path')->store('staff-photos', 'public');
            $staff->update(['photo_path' => $path]);

            return $this->successResponse(
                ['photo_url' => Storage::url($path)],
                'Profile photo updated successfully'
            );

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Photo upload failed', 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|different:current_password',
            'confirm_password' => 'required|string|same:new_password'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn($error) => $error[0]),
                $validator->errors()->first()
            );
        }

        try {
            $user = auth()->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->errorResponse('Unauthorized', 'Current password is incorrect', 401);
            }

            $user->update(['password' => Hash::make($request->new_password)]);

            return $this->successResponse(null, 'Password changed successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Password change failed', $e->getMessage(), 500);
        }
    }

    /**
     * Get user profile data
     */
    public function getProfile()
    {
        try {
            $user = auth()->user()->load(['staff.branch', 'staff.section', 'role']);
            
            return $this->successResponse($user, 'Profile retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve profile', 500);
        }
    }
}