<?php

namespace App\Http\Controllers;

use App\Models\ParkingLocation;
use App\Models\Municipal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParkingLocationController extends BaseController
{
    // List all parking locations
    public function index(Request $request)
    {
        $query = ParkingLocation::with(['municipal']);

        if ($request->has('search')) {
            $query->where('location_name', 'like', '%' . $request->search . '%')
                ->orWhereHas('municipal', function ($q) use ($request) {
                    $q->where('municipal_name', 'like', '%' . $request->search . '%');
                });
        }

        if ($request->has('municipal_id')) {
            $query->where('municipal_id', $request->municipal_id);
        }

        $locations = $query->get();

        return $this->successResponse($locations, 'Parking locations retrieved successfully');
    }

    // Create new location
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_name' => 'required|string|max:100',
            'municipal_id' => 'required|exists:municipals,municipal_id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $location = ParkingLocation::create($validator->validated());

        return $this->successResponse($location->load('municipal'), 'Parking location created successfully', 201);
    }

    // Show single location
    public function show($id)
    {
        $location = ParkingLocation::with(['municipal', 'parkingEntries'])->find($id);

        if (!$location) {
            return $this->errorResponse('Parking location not found', 'Parking location not found', 404);
        }

        return $this->successResponse($location, 'Parking location retrieved successfully');
    }

    // Update location
    public function update(Request $request, ParkingLocation $parkingLocation)
    {
        $validator = Validator::make($request->all(), [
            'location_name' => 'sometimes|required|string|max:100',
            'municipal_id' => 'sometimes|required|exists:municipals,municipal_id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $parkingLocation->update($validator->validated());

        return $this->successResponse($parkingLocation->load('municipal'), 'Parking location updated successfully');
    }

    // Delete location
    public function destroy(ParkingLocation $parkingLocation)
    {
        if ($parkingLocation->parkingEntries()->count() > 0) {
            return $this->errorResponse('Cannot delete parking location with associated parking entries', 'Cannot delete parking location with associated parking entries', 409);
        }

        $parkingLocation->delete();

        return $this->successResponse(null, 'Parking location deleted successfully');
    }

    // Parking entries for this location
    public function parkingEntries(ParkingLocation $parkingLocation)
    {
        $entries = $parkingLocation->parkingEntries()
            ->with(['vehicleType', 'officer'])
            ->orderBy('entry_time', 'desc')
            ->get();

        // Fix for DataTables if needed
        $entries->each(function ($entry) {
            $entry->officer = $entry->officer ? $entry->officer->name : '-';
        });

        return $this->successResponse($entries, 'Parking entries for this location retrieved successfully');
    }
}
