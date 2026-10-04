<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SectionController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $query = Section::with(['department', 'head', 'staff']);

            if ($request->has('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            $sections = $query->get();
            return $this->successResponse($sections, 'Sections retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve sections', 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sections',
            'department_id' => 'required|exists:departments,id',
            'head_id' => 'nullable|exists:staff,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        try {
            $section = Section::create($request->all());
            return $this->successResponse($section->load(['department', 'head']), 'Section created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Section creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $section = Section::with(['department', 'head'])->find($id);

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            return $this->successResponse($section, 'Section retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve section', 500);
        }
    }
    public function byDepartment($id)
    {
        try {
            $section = Section::with(['department', 'head'])->where(["department_id" => $id])->get();

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            return $this->successResponse($section, 'Section retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve section', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $section = Section::find($id);

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'code' => 'string|unique:sections,code,'.$section->id,
                'department_id' => 'exists:departments,id',
                'head_id' => 'nullable|exists:staff,id',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
            }

            $section->update($request->all());
            return $this->successResponse($section->load(['department', 'head']), 'Section updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Section update failed', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $section = Section::find($id);

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            if ($section->staff()->count() > 0) {
                return $this->errorResponse(
                    'Conflict',
                    'Cannot delete section with assigned staff',
                    409
                );
            }

            $section->delete();
            return $this->successResponse(null, 'Section deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Section deletion failed', 500);
        }
    }

    public function restore($id)
    {
        try {
            $section = Section::withTrashed()->find($id);

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            $section->restore();
            return $this->successResponse($section, 'Section restored successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Section restoration failed', 500);
        }
    }

    public function assignHead(Request $request, $id)
    {
        try {
            $section = Section::find($id);

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            $validator = Validator::make($request->all(), [
                'staff_id' => 'required|exists:staff,id'
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Validation error', 422);
            }

            // Verify staff belongs to the same department
            $staff = Staff::find($request->staff_id);
            if ($staff->department_id !== $section->department_id) {
                return $this->errorResponse(
                    'Conflict',
                    'Staff member must belong to the same department',
                    409
                );
            }

            $section->update(['head_id' => $request->staff_id]);
            return $this->successResponse($section->load('head'), 'Section head assigned successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to assign section head', 500);
        }
    }

    public function staff($id)
    {
        try {
            $section = Section::find($id);

            if (!$section) {
                return $this->errorResponse('Section not found', 'Not found', 404);
            }

            $staff = $section->staff()->with('user')->get();
            return $this->successResponse($staff, 'Section staff retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'Failed to retrieve section staff', 500);
        }
    }
}
