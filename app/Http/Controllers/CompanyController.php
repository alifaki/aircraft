<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CompanyController extends BaseController
{
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $companies = $query->get();

        return $this->successResponse($companies, 'Companies retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'required|string|max:200|unique:companies',
            'company_name' => 'required|string|max:100',
            'description' => 'required|string|max:200',
            'logo' => 'nullable|string|max:255',
            'location' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'email' => 'required|email|max:50|unique:companies',
            'phone' => 'required|string|max:30|unique:companies',
            'postal_address' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:100',
            'facebook' => 'nullable|url|max:100',
            'twitter' => 'nullable|url|max:100',
            'instagram' => 'nullable|url|max:100',
            'youtube' => 'nullable|url|max:100',
            'map_link' => 'nullable|url|max:300',
            'donation' => 'numeric',
            'status' => 'string|max:20|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), $validator->errors()->first());
        }

        $company = Company::create($validator->validated());

        return $this->successResponse($company, 'Company created successfully', 201);
    }

    public function show($id)
    {
        $company = Company::find($id);

        if (is_null($company)) {
            return $this->errorResponse('Company not found', 'Not found', 404);
        }

        return $this->successResponse($company, 'Company retrieved successfully');
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'string|max:20|unique:companies,app_id,'.$company->id,
            'company_name' => 'string|max:100',
            'description' => 'string|max:200',
            'logo' => 'nullable|string|max:255',
            'location' => 'string|max:100',
            'city' => 'string|max:100',
            'country' => 'string|max:100',
            'email' => 'email|max:50|unique:companies,email,'.$company->id,
            'phone' => 'string|max:30|unique:companies,phone,'.$company->id,
            'postal_address' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:100',
            'facebook' => 'nullable|url|max:100',
            'twitter' => 'nullable|url|max:100',
            'instagram' => 'nullable|url|max:100',
            'youtube' => 'nullable|url|max:100',
            'map_link' => 'nullable|url|max:300',
            'donation' => 'numeric',
            'status' => 'string|max:20|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]),  $validator->errors()->first());
        }

        $company->update($validator->validated());

        return $this->successResponse($company, 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        if ($company->branches()->count() > 0) {
            return $this->errorResponse('Conflict', 'Cannot delete company with branches', 409);
        }

        $company->delete();
        return $this->successResponse(null, 'Company deleted successfully');
    }
}
