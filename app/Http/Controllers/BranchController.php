<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BranchController extends BaseController
{
    /**
     * Display a listing of the branches with optional filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Branch::with('company');

            // Apply company filter if provided
            if ($request->has('company_id') && $request->company_id) {
                $query->where('company_id', $request->company_id);
            }

            // Apply status filter if provided
            if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
                $query->where('status', $request->status);
            }

            $branches = $query->get();

            return $this->successResponse($branches, 'Branches retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve branches: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created branch in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:companies,id',
                'branch_name' => 'required|string|max:100',
                'branch_code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('branches')->where(function ($query) use ($request) {
                        return $query->where('company_id', $request->company_id);
                    })
                ],
                'description' => 'nullable|string|max:200',
                'location' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'manager_name' => 'required|string|max:100',
                'manager_phone' => [
                    'required',
                    'string',
                    'max:30',
                    Rule::unique('branches')->where(function ($query) use ($request) {
                        return $query->where('company_id', $request->company_id);
                    })
                ],
                'manager_email' => [
                    'required',
                    'email',
                    'max:50',
                    Rule::unique('branches')->where(function ($query) use ($request) {
                        return $query->where('company_id', $request->company_id);
                    })
                ],
                'postal_address' => 'nullable|string|max:100',
                'map_link' => 'nullable|url|max:300',
                'status' => 'required|string|max:20|in:active,inactive',
            ], [
                'branch_code.unique' => 'The branch code must be unique within the company.',
                'manager_phone.unique' => 'The manager phone must be unique within the company.',
                'manager_email.unique' => 'The manager email must be unique within the company.',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]),
                    'Validation error'
                );
            }

            $branch = Branch::create($validator->validated());

            // Load company relationship for the response
            $branch->load('company');

            return $this->successResponse($branch, 'Branch created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create branch: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified branch.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $branch = Branch::with('company')->find($id);

            if (is_null($branch)) {
                return $this->errorResponse('Branch not found', 'Not found', 404);
            }

            return $this->successResponse($branch, 'Branch retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve branch: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified branch in storage.
     *
     * @param Request $request
     * @param Branch $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Branch $branch)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'exists:companies,id',
                'branch_name' => 'string|max:100',
                'branch_code' => [
                    'string',
                    'max:20',
                    Rule::unique('branches')
                        ->where(function ($query) use ($request, $branch) {
                            return $query->where('company_id', $request->company_id ?? $branch->company_id);
                        })
                        ->ignore($branch->id)
                ],
                'description' => 'nullable|string|max:200',
                'location' => 'string|max:100',
                'city' => 'string|max:100',
                'country' => 'string|max:100',
                'manager_name' => 'string|max:100',
                'manager_phone' => [
                    'string',
                    'max:30',
                    Rule::unique('branches')
                        ->where(function ($query) use ($request, $branch) {
                            return $query->where('company_id', $request->company_id ?? $branch->company_id);
                        })
                        ->ignore($branch->id)
                ],
                'manager_email' => [
                    'email',
                    'max:50',
                    Rule::unique('branches')
                        ->where(function ($query) use ($request, $branch) {
                            return $query->where('company_id', $request->company_id ?? $branch->company_id);
                        })
                        ->ignore($branch->id)
                ],
                'postal_address' => 'nullable|string|max:100',
                'map_link' => 'nullable|url|max:300',
                'status' => 'string|max:20|in:active,inactive',
            ], [
                'branch_code.unique' => 'The branch code must be unique within the company.',
                'manager_phone.unique' => 'The manager phone must be unique within the company.',
                'manager_email.unique' => 'The manager email must be unique within the company.',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]),
                    'Validation error',
                    422
                );
            }

            $branch->update($validator->validated());
            $branch->load('company');

            return $this->successResponse($branch, 'Branch updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update branch: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified branch from storage.
     *
     * @param Branch $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Branch $branch)
    {
        try {
            // Check for related records
            if ($branch->bankAccounts()->count() > 0 || $branch->bills()->count() > 0) {
                return $this->errorResponse(
                    'Conflict',
                    'Cannot delete branch because it has related bank accounts or bills. Please delete those records first.',
                    409
                );
            }

            $branch->delete();
            return $this->successResponse(null, 'Branch deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete branch: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get branches by company ID.
     *
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCompany($companyId)
    {
        try {
            // Validate company exists
            if (!Company::where('id', $companyId)->exists()) {
                return $this->errorResponse('Company not found', 'Not found', 404);
            }

            $branches = Branch::where('company_id', $companyId)->get();
            return $this->successResponse($branches, 'Branches retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve branches: ' . $e->getMessage(), 500);
        }
    }
}
