<?php

namespace App\Http\Controllers;

use App\ApiIntegration\TextMessage;
use App\ApiIntegration\SamisTextMessage;
use App\Models\Staff;
use App\Models\User;
use App\Models\Branch;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\SamisMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffTemplateExport;
use App\Imports\StaffImport;
use Illuminate\Support\Str;

class StaffController extends BaseController
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
        $query = Staff::with(['user.role', 'branch', 'section'])
            ->whereHas('branch', function($q) {
                $q->where('company_id', auth()->user()->staffs->branch->company_id);
            });

        if ($request->has('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query->get();
        return $this->successResponse($staff, 'Staff retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'branch_id' => 'required|exists:branches,id',
            'section_id' => 'required|exists:sections,id',
            'employee_number' => 'required|string|max:50|unique:staff',
            'email' => 'nullable|email|max:100|unique:staff',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:100',
            'status' => 'string|max:20|in:active,inactive',
            'create_user_account' => 'boolean',
            'username' => 'required_if:create_user_account,true|string|max:50|unique:users',
            'password' => 'required_if:create_user_account,true|string|min:8',
            'role_id' => 'required_if:create_user_account,true|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $staff = Staff::create($request->except(['create_user_account', 'username', 'password', 'role_id']));

            if ($request->create_user_account) {
                $user = User::create([
                    'staff_id' => $staff->id,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id,
                    'status' => 'active'
                ]);
            }

            DB::commit();
            return $this->successResponse($staff->load(['user.role', 'branch', 'section']), 'Staff created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 'Staff creation failed', 500);
        }
    }

    public function show(Staff $staff)
    {
        return $this->successResponse($staff->load(['user.role', 'branch', 'section']), 'Staff retrieved successfully');
    }

    public function update(Request $request, Staff $staff)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'branch_id' => 'exists:branches,id',
            'section_id' => 'exists:sections,id',
            'employee_number' => 'string|max:50|unique:staff,employee_number,'.$staff->id,
            'email' => 'email|max:100|unique:staff,email,'.$staff->id,
            'phone' => 'string|max:20',
            'position' => 'string|max:100',
            'status' => 'string|max:20|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
        }

        $staff->update($validator->validated());
        return $this->successResponse($staff->load(['user.role', 'branch', 'section']), 'Staff updated successfully');
    }

    public function destroy(Staff $staff)
    {
        try {
            if ($staff->user) {
                return $this->errorResponse('Cannot delete staff with active user account', 'Conflict', 409);
            }

            $staff->delete();
            return $this->successResponse(null, 'Staff deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Section deletion failed', 'Can not delete staff, has relation with section', 500);
        }
    }

    public function createUserAccount(Request $request, Staff $staff)
    {
        if ($staff->user) {
            return $this->errorResponse('User account already exists', 'Conflict', 409);
        }
        $strongPassword = Str::random(12); // 12 characters for extra strength

        $request->merge(['password' => $strongPassword]);

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
        }

        $user = User::create([
            'staff_id' => $staff->id,
            'username' => $request->username,
            'password' => Hash::make($strongPassword),
            'role_id' => $request->role_id,
            'status' => 'active'
        ]);

        if ($staff->phone) {
            // Keep only digits (or digits + leading + if required by API)
            $phone = preg_replace('/[^\d]/', '', $staff->phone);

            $fullName = trim($staff->first_name." ".$staff->last_name);

            $message  = "Dear $fullName,\n";
            $message .= "Your SAMIS account has been created with username: {$request->username}\n";
            $message .= "Please visit ".config('app.url')."/login and click Forgot Password to create your password\n\n";

            // Send email
            $details = [
                'title' => 'Welcome to SAMIS. You can now start using your account.',
                'body' => $message
            ];

            Mail::to($staff->email ?? "info@arif.technology")->send(new SamisMail($details));

            // $this->textMessage->sendBeam($phone, $message);
            $this->arifMessage->sendTextMessage($phone, $message);
        }
        return $this->successResponse($user, 'User account created successfully', 201);
    }

    /**
     * Download staff template
     */
    public function downloadTemplate()
    {
        return Excel::download(new StaffTemplateExport(), 'staff_template.xlsx');
    }

    /**
     * Upload staff data
     */
    public function upload(Request $request)
    {
        $request->merge(['update_existing' => $request->update_existing == "on" ? true : false]);
        $validator = Validator::make($request->all(), [
            'staff_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
            'update_existing' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error',$validator->errors()->first(),  422);
        }

        try {
            $updateExisting = $request->boolean('update_existing', false);
            
            $import = new StaffImport($updateExisting);
            Excel::import($import, $request->file('staff_file'));

            $result = [
                'total_records' => $import->getRowCount(),
                'successful' => $import->getRowCount() - count($import->getErrors()),
                'failed' => count($import->getErrors()),
                'errors' => $import->getErrors()
            ];

            $message = "Staff data uploaded successfully. " . 
                      "Total: {$result['total_records']}, " .
                      "Successful: {$result['successful']}, " .
                      "Failed: {$result['failed']}";

            return $this->successResponse($result, $message);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Upload failed', 500);
        }
    }
}