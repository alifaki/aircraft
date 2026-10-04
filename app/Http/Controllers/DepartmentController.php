<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $query = Department::with(['head', 'sections']);

            // Add search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }

            // Add status filter
            if ($request->has('status')) {
                $query->where('is_active', $request->status === 'active');
            }

            $departments = $query->get();

            return $this->successResponse($departments, 'Departments retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve departments', 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'required|string',
            'head_ofd_id' => 'nullable|exists:staff,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        try {
            $department = Department::create($request->all());
            return $this->successResponse($department, 'Department created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Department creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $department = Department::with(['head', 'sections'])->find($id);

            if (!$department) {
                return $this->errorResponse('Department not found', 'Not found', 404);
            }

            return $this->successResponse($department, 'Department retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve department', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return $this->errorResponse('Department not found', 'Not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'code' => 'string|max:50|unique:departments,code,' . $department->id,
                'description' => 'string',
                'head_ofd_id' => 'nullable|exists:staff,id',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
            }

            $department->update($request->all());
            return $this->successResponse($department, 'Department updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Department update failed', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return $this->errorResponse('Department not found', 'Not found', 404);
            }

            // Check if department has sections before deleting
            if ($department->sections()->count() > 0) {
                return $this->errorResponse(
                    'Conflict',
                    'Cannot delete department with assigned sections',
                    409
                );
            }

            $department->delete();
            return $this->successResponse(null, 'Department deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Department deletion failed', 500);
        }
    }

    public function restore($id)
    {
        try {
            $department = Department::withTrashed()->find($id);

            if (!$department) {
                return $this->errorResponse('Department not found', 'Not found', 404);
            }

            $department->restore();
            return $this->successResponse($department, 'Department restored successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Department restoration failed', 500);
        }
    }

    public function assignHead(Request $request, $id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return $this->errorResponse('Department not found', 'Not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'staff_id' => 'required|exists:staff,id'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Validation error', 422);
            }

            $department->update(['head_ofd_id' => $request->staff_id]);
            return $this->successResponse($department, 'Department head assigned successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to assign department head', 500);
        }
    }
}
