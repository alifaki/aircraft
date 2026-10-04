<?php

namespace App\Http\Controllers;

use App\Models\ParkingEntry;
use App\Models\VehicleType;
use App\Models\ParkingLocation;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ParkingEntryController extends BaseController
{
    public function index(Request $request)
    {
        $query = ParkingEntry::with(['vehicleType', 'location.municipal', 'officer']);

        if ($request->has('search')) {
            $query->where('plate_number', 'like', '%' . $request->search . '%')
                ->orWhere('phone_number', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->whereNull('exit_time');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('exit_time');
            }
        }

        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->has('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('entry_time', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('entry_time', '<=', $request->date_to);
        }

        $entries = $query->orderBy('entry_time', 'desc')->get();

        /* ================= FIX FOR DATATABLES (DO NOT REMOVE) ================= */
        $entries->each(function ($entry) {
            $entry->officer = $entry->officer ? $entry->officer->name : '-';
        });
        /* ===================================================================== */

        return $this->successResponse($entries, 'Parking entries retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required|exists:vehicle_types,vehicle_type_id',
            'plate_number' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'location_id' => 'required|exists:parking_locations,location_id',
            'officer_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $vehicleType = VehicleType::find($request->vehicle_type_id);

        $data = $validator->validated();
        $data['entry_time'] = now();
        $data['rate_per_hour'] = $vehicleType->hourly_rate;

        $entry = ParkingEntry::create($data);

        return $this->successResponse(
            $entry->load(['vehicleType', 'location.municipal', 'officer']),
            'Parking entry created successfully',
            201
        );
    }

    public function syncVehicles(Request $request)
    {
        $vehicles = $request->all();

        if (!is_array($vehicles) || array_keys($vehicles) === range(0, count($vehicles) - 1)) {
            // $vehicles is a numerically indexed array (list of arrays)
        } else {
            // Single object sent instead of array
            $vehicles = [$vehicles];
        }

        $results = [];
        $errors = [];
        foreach ($vehicles as $vehicle) {
            $validator = Validator::make($vehicle, [
                'id' => 'required', // entry_id
                'parkingAreaId' => 'required|exists:parking_locations,location_id',
                'vehicleType' => 'required|string',
                'vehicleTypeId' => 'required|exists:vehicle_types,vehicle_type_id',
                'plateNumber' => 'required|string|max:20',
                'attendantId' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'id' => $vehicle['id'] ?? null,
                    'errors' => $validator->errors()
                ];
                continue;
            }

            $data = [
                'external_id'     => $vehicle['id'],
                'vehicle_type_id' => $vehicle['vehicleTypeId'],
                'plate_number'    => $vehicle['plateNumber'],
                'phone_number'    => $vehicle['driverPhone'] ?? null,
                'location_id'     => $vehicle['parkingAreaId'],
                'officer_id'      => $vehicle['attendantId'],
                'entry_time'      => $vehicle['entryTime'] ?? now(),
                'exit_time'       => $vehicle['exitTime'] ?? null,
                'rate_per_hour'   => $vehicle['ratePerHour'] ?? null,
                'total_amount'    => $vehicle['totalAmount'] ?? null,
            ];

            $entry = ParkingEntry::updateOrCreate(
                ['entry_id' => $vehicle['id']],
                $data
            );

            // Only create a bill if there is NOT a pending unexpired bill for this entry
            $parkingEntryHasPendingBill = Bill::where('parking_entry_id', $entry->entry_id)
                ->where('status', 'pending')
                ->where('expires_at', '>=', now())
                ->exists();

            if ($parkingEntryHasPendingBill) {
                $results[] = $entry->load(['vehicleType', 'location.municipal', 'officer']);
                continue;
            }

            // Only proceed to create bill and bill items if the exit_time exists and total_amount greater than 0
            if ($entry->exit_time && $vehicle['totalAmount'] > 0) {
                $bill = Bill::create([
                    'parking_entry_id' => $entry->entry_id,
                    'customer_id' => $entry->plate_number,
                    'customer_type' => 'parking',
                    'control_number' => 'PARKING-' . $entry->plate_number . "-" . $entry->entry_id,
                    'customer_name' => $entry->plate_number,
                    'description' => 'Parking fee',
                    'bill_option' => 'full',
                    'amount' => $vehicle['totalAmount'],
                    'expires_at' => now()->addYears(1),
                ]);

                // !! Fix: Use createMany instead of create and ensure the array shape is correct.
                $billItemData = [
                    [
                        'bill_id' => $bill->id,
                        'item_reference' => 'PARKING_FEE',
                        'payment_reference' => 'PARKING_FEE_' . time() . '_' . $entry->entry_id,
                        'amount' => $vehicle['totalAmount'],
                        'gfs_code' => '001',
                    ],
                ];

                $bill->items()->createMany($billItemData);
            }

            $results[] = $entry->load(['vehicleType', 'location.municipal', 'officer']);
        }

        if (!empty($errors)) {
            return $this->errorResponse(['vehicles' => $errors], 'Some vehicles failed to sync', 422);
        }

        return $this->successResponse(
            $results,
            'Vehicles synced successfully',
            200
        );
    }

    public function show($id)
    {
        $entry = ParkingEntry::with(['vehicleType', 'location.municipal', 'officer'])->find($id);

        if (!$entry) {
            return $this->errorResponse('Parking entry not found', 'Parking entry not found', 404);
        }

        return $this->successResponse($entry, 'Parking entry retrieved successfully');
    }

    public function update(Request $request, ParkingEntry $parkingEntry)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'sometimes|required|exists:vehicle_types,vehicle_type_id',
            'plate_number' => 'sometimes|required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'location_id' => 'sometimes|required|exists:parking_locations,location_id',
            'exit_time' => 'nullable|date',
            'rate_per_hour' => 'sometimes|required|numeric|min:0|max:999999.99',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        if (isset($data['exit_time'])) {
            $exitTime = Carbon::parse($data['exit_time']);
            $entryTime = $parkingEntry->entry_time;

            $hours = ceil($entryTime->diffInMinutes($exitTime) / 60);
            $ratePerHour = $data['rate_per_hour'] ?? $parkingEntry->rate_per_hour;
            $data['total_amount'] = $hours * $ratePerHour;
        }

        if (isset($data['vehicle_type_id']) && $data['vehicle_type_id'] != $parkingEntry->vehicle_type_id) {
            $vehicleType = VehicleType::find($data['vehicle_type_id']);
            $data['rate_per_hour'] = $vehicleType->hourly_rate;
        }

        $parkingEntry->update($data);

        return $this->successResponse(
            $parkingEntry->load(['vehicleType', 'location.municipal', 'officer']),
            'Parking entry updated successfully'
        );
    }

    public function destroy(ParkingEntry $parkingEntry)
    {
        $parkingEntry->delete();
        return $this->successResponse(null, 'Parking entry deleted successfully');
    }

    public function checkout(Request $request, ParkingEntry $parkingEntry)
    {
        if ($parkingEntry->exit_time) {
            return $this->errorResponse(
                'Parking entry already checked out',
                'Parking entry already checked out',
                400
            );
        }

        $exitTime = now();
        $parkingEntry->exit_time = $exitTime;

        $hours = ceil($parkingEntry->entry_time->diffInMinutes($exitTime) / 60);
        $parkingEntry->total_amount = $hours * $parkingEntry->rate_per_hour;

        $parkingEntry->save();

        $bill = Bill::create([
            'parking_entry_id' => $parkingEntry->entry_id,
            'bill_option' => 'full',
            'amount' => $parkingEntry->total_amount,
            'expires_at' => now()->addHours(1),
        ]);

        $billItems = [
            [
                'bill_id' => $bill->id,
                'item_reference' => 'PARKING_FEE',
                'payment_reference' => 'PARKING_FEE_' . time(),
                'amount' => $parkingEntry->total_amount,
                'gfs_code' => '001',
            ]
        ];
        
        $bill->items()->create($billItems);

        return $this->successResponse(
            $parkingEntry->load(['vehicleType', 'location.municipal', 'officer', 'bill.items']),
            'Parking entry checked out successfully'
        );
    }

    public function activeParkings(Request $request)
    {
        $query = ParkingEntry::with(['vehicleType', 'location.municipal', 'officer'])
            ->whereNull('exit_time');

        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $activeParkings = $query->orderBy('entry_time', 'asc')->get();

        return $this->successResponse($activeParkings, 'Active parkings retrieved successfully');
    }

    public function reports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'group_by' => 'nullable|in:day,week,month,year,location,vehicle_type',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), $validator->errors()->first(), 422);
        }

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $query = ParkingEntry::whereBetween('entry_time', [$startDate, $endDate])
            ->whereNotNull('exit_time')
            ->whereNotNull('total_amount');

        if ($request->has('group_by')) {
            switch ($request->group_by) {
                case 'day':
                    $query->selectRaw('DATE(entry_time) as period, COUNT(*) as count, SUM(total_amount) as revenue')
                        ->groupBy('period');
                    break;

                case 'location':
                    $query->selectRaw('location_id, COUNT(*) as count, SUM(total_amount) as revenue')
                        ->groupBy('location_id')
                        ->with(['location']);
                    break;

                case 'vehicle_type':
                    $query->selectRaw('vehicle_type_id, COUNT(*) as count, SUM(total_amount) as revenue')
                        ->groupBy('vehicle_type_id')
                        ->with(['vehicleType']);
                    break;
            }
        }

        $reports = $query->get();

        $summary = [
            'total_entries' => $reports->sum('count'),
            'total_revenue' => $reports->sum('revenue'),
            'average_per_entry' =>
                $reports->sum('count') > 0
                    ? $reports->sum('revenue') / $reports->sum('count')
                    : 0,
        ];

        return $this->successResponse([
            'reports' => $reports,
            'summary' => $summary,
            'date_range' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
        ], 'Parking reports retrieved successfully');
    }

    public function getActiveBill($plateNumber){
        // Remove any spaces and convert to uppercase
        $cleanPlateNumber = strtoupper(str_replace(' ', '', $plateNumber));

        // Validate that the plate number is not empty and is alphanumeric (optionally adjust as per your format)
        if (empty($cleanPlateNumber) || !preg_match('/^[A-Z0-9]+$/', $cleanPlateNumber)) {
            return $this->errorResponse('Invalid plate number', 'Invalid plate number', 422);
        }

        $bill = Bill::with(['items.parameter', 'entry.vehicleType', 'entry.location.municipal', 'entry.officer'])
            ->where('status', 'pending')
            ->where('expires_at', '>=', now())
            ->whereHas('entry', function($query) use ($cleanPlateNumber) {
                $query->where('plate_number', $cleanPlateNumber);
            })
            ->get();
            
        if ($bill->count() > 0) {
            return $this->successResponse($bill, 'Active bill retrieved successfully');
        }

        return $this->errorResponse('No active bill found', 'No active bill found', 404);
    }
}
