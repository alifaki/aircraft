<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SamisMail;
use App\ApiIntegration\TextMessage;
use App\ApiIntegration\SamisTextMessage;
class UserController extends BaseController
{
    private TextMessage $textMessage;
    private SamisTextMessage $arifMessage;
    
    public function __construct(TextMessage $textMessage, SamisTextMessage $arifMessage)
    {
        $this->textMessage = $textMessage;
        $this->arifMessage = $arifMessage;
    }

   public function index(Request $request)
    {
        try {
            $query = User::with(['role', 'staff', 'parkingLocation']);
            
            // Filter by role if provided
            if ($request->filled('role_id')) {
                $query->where('role_id', $request->role_id);
            }

            // Filter by status if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Search by username or staff name
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                    ->orWhereHas('staff', function ($q2) use ($search) {
                        $q2->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
                });
            }

            $users = $query->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role ? [
                        'id' => $user->role->id,
                        'name' => $user->role->role_name ?? '-'
                    ] : ['id' => null, 'name' => '-'],
                    'staff' => $user->staff ? [
                        'id' => $user->staff->id,
                        'first_name' => $user->staff->first_name ?? '-',
                        'last_name' => $user->staff->last_name ?? '-'
                    ] : ['id' => null, 'first_name' => '-', 'last_name' => '-'],
                    'status' => $user->status ?? 'inactive',
                ];
            });
           
            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve users', 500);
        }
    }

    public function store(Request $request)
    {
        // Generate strong password automatically
        $strongPassword = Str::random(12); // 12 characters for strength
        $request->merge(['password' => $strongPassword]);
    
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'staff_id' => 'nullable|exists:staff,id',
            'status' => 'string|in:active,inactive',
            'parking_location_id' => 'nullable|exists:parking_locations,location_id'
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn($error) => $error[0]),
                $validator->errors()->first()
            );
        }
    
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($strongPassword),
                'role_id' => $request->role_id,
                'staff_id' => $request->staff_id,
                'status' => $request->status ?? 'active',
                'parking_location_id' => $request->parking_location_id
            ]);
    
            // Notify staff if staff_id exists
            if ($request->staff_id) {
                $staff = \App\Models\Staff::find($request->staff_id);
                if ($staff) {
                    $fullName = trim($staff->first_name . ' ' . $staff->last_name);
    
                    // Email notification
                    $message = "Dear $fullName,\n";
                    $message .= "Your SAMIS account has been created with username: {$request->username}\n";
                    $message .= "Please visit ".config('app.url')."/login and click Forgot Password to create your password\n\n";
    
                    $details = [
                        'title' => 'Welcome to SAMIS. You can now start using your account.',
                        'body' => $message
                    ];
    
                    Mail::to($staff->email ?? 'info@arif.technology')->send(new \App\Mail\SamisMail($details));
    
                    // SMS notification (assuming your textMessage service is available)
                    if ($staff->phone) {
                        $phone = preg_replace('/[^\d]/', '', $staff->phone);
                        // $this->textMessage->sendBeam($phone, $message);
                        $this->arifMessage->sendTextMessage($phone, $message);
                    }
                }
            }
    
            return $this->successResponse($user->load(['role', 'staff']), 'User created successfully', 201);
    
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'User creation failed', 500);
        }
    }    

    public function show($id)
    {
        try {
            $user = User::with(['role', 'staff', 'parkingLocation'])->find($id);

            if (!$user) {
                return $this->errorResponse('User not found', 'Not found', 404);
            }

            return $this->successResponse($user, 'User retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve user', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->errorResponse('User not found', 'Not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'username' => 'string|unique:users,username,'.$user->id,
                'role_id' => 'exists:roles,id',
                'staff_id' => 'nullable|exists:staff,id',
                'status' => 'string|in:active,inactive',
                'parking_location_id' => 'nullable|exists:parking_locations,location_id'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
            }

            $user->update($request->all());
            return $this->successResponse($user->load(['role', 'staff', 'parkingLocation']), 'User updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'User update failed', 500);
        }
    }
    public function restore($id)
    {
        try {
            $user = User::withTrashed()->find($id);

            if (!$user) {
                return $this->errorResponse('User not found', 'Not found', 404);
            }

            $user->restore();
            return $this->successResponse($user, 'User restored successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'User restoration failed', 500);
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->errorResponse('User not found', 'Not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:current_password',
                'confirm_password' => 'required|string|same:new_password'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->errorResponse('Unauthorized', 'Current password is incorrect',  401);
            }

            $user->update(['password' => Hash::make($request->new_password)]);
            return $this->successResponse(null, 'Password changed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Password change failed', $e->getMessage(), 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return $this->errorResponse('User not found', 'Not found', 404);
            }

            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            return $this->successResponse(
                ['status' => $newStatus],
                'User status updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to update user status', 500);
        }
    }
}
