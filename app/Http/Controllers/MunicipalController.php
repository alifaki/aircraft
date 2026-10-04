<?php

namespace App\Http\Controllers;

use App\Models\Municipal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MunicipalController extends BaseController
{
    public function index(Request $request)
    {
        $query = Municipal::query();

        if ($request->has('search')) {
            $query->where('municipal_name', 'like', '%' . $request->search . '%')
                ->orWhere('region', 'like', '%' . $request->search . '%');
        }

        $municipals = $query->get();

        return $this->successResponse($municipals, 'Municipals retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'municipal_name' => 'required|string|max:100|unique:municipals',
            'region' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $municipal = Municipal::create($validator->validated());

        return $this->successResponse($municipal, 'Municipal created successfully', 201);
    }

    public function show($id)
    {
        $municipal = Municipal::with(['parkingLocations'])->find($id);

        if (!$municipal) {
            return $this->errorResponse('Municipal not found', 'Municipal not found', 404);
        }

        return $this->successResponse($municipal, 'Municipal retrieved successfully');
    }

    public function update(Request $request, Municipal $municipal)
    {
        $validator = Validator::make($request->all(), [
            'municipal_name' => 'sometimes|required|string|max:100|unique:municipals,municipal_name,' . $municipal->municipal_id . ',municipal_id',
            'region' => 'sometimes|required|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $municipal->update($validator->validated());

        return $this->successResponse($municipal, 'Municipal updated successfully');
    }

    public function destroy(Municipal $municipal)
    {
        if ($municipal->parkingLocations()->count() > 0) {
            return $this->errorResponse('Cannot delete municipal with associated parking locations', 'Cannot delete municipal with associated parking locations', 409);
        }

        $municipal->delete();

        return $this->successResponse(null, 'Municipal deleted successfully');
    }

    public function parkingLocations(Municipal $municipal)
    {
        $locations = $municipal->parkingLocations()->get();

        return $this->successResponse($locations, 'Parking locations for this municipal retrieved successfully');
    }
}