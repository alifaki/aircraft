<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleTypeController extends BaseController
{
    public function index(Request $request)
    {
        $query = VehicleType::query();

        if ($request->has('search')) {
            $query->where('type_name', 'like', '%' . $request->search . '%');
        }

        $vehicleTypes = $query->get();

        return $this->successResponse($vehicleTypes, 'Vehicle types retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'required|string|max:50|unique:vehicle_types',
            'hourly_rate' => 'required|numeric|min:0|max:999999.99',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $vehicleType = VehicleType::create($validator->validated());

        return $this->successResponse($vehicleType, 'Vehicle type created successfully', 201);
    }

    public function show($id)
    {
        $vehicleType = VehicleType::with(['parkingEntries'])->find($id);

        if (!$vehicleType) {
            return $this->errorResponse('Vehicle type not found', 'Vehicle type not found', 404);
        }

        return $this->successResponse($vehicleType, 'Vehicle type retrieved successfully');
    }

    public function update(Request $request, VehicleType $vehicleType)
    {
        $validator = Validator::make($request->all(), [
            'type_name' => 'sometimes|required|string|max:50|unique:vehicle_types,type_name,' . $vehicleType->vehicle_type_id . ',vehicle_type_id',
            'hourly_rate' => 'sometimes|required|numeric|min:0|max:999999.99',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $vehicleType->update($validator->validated());

        return $this->successResponse($vehicleType, 'Vehicle type updated successfully');
    }

    public function destroy(VehicleType $vehicleType)
    {
        if ($vehicleType->parkingEntries()->count() > 0) {
            return $this->errorResponse('Cannot delete vehicle type with associated parking entries', 'Cannot delete vehicle type with associated parking entries', 409);
        }

        $vehicleType->delete();

        return $this->successResponse(null, 'Vehicle type deleted successfully');
    }

    public function parkingEntries(VehicleType $vehicleType)
    {
        $entries = $vehicleType->parkingEntries()
            ->with(['location.municipal', 'officer'])
            ->orderBy('entry_time', 'desc')
            ->get();

        return $this->successResponse($entries, 'Parking entries for this vehicle type retrieved successfully');
    }
}