<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingEntry;
use App\Http\Controllers\PaymentController; // ✅ Add this line

class PaymentController extends Controller
{
    /**
     * Display the search page
     */
    public function index()
    {
        return view('payments.search'); // Blade view
    }

    /**
     * AJAX: Search Parking Entries by Plate Number
     */
    public function searchPlate(Request $request)
    {
        $plate = $request->get('plate_number');

        if (!$plate) {
            return response()->json([], 422);
        }

        $entries = ParkingEntry::where('plate_number', 'like', "%{$plate}%")
            ->with(['location', 'officer', 'vehicleType'])
            ->get()
            ->map(function ($entry) {

                // Calculate duration
                $duration = $entry->entry_time->format('Y-m-d H:i');
                if ($entry->exit_time) {
                    $duration .= ' - ' . $entry->exit_time->format('Y-m-d H:i');
                } else {
                    $duration .= ' - N/A';
                }

                // Calculate amount if exit_time exists or fallback to rate_per_hour
                $amount = $entry->total_amount ?? $entry->rate_per_hour;

                return [
                    'id' => $entry->entry_id,
                    'receiptNumber' => 'REC-' . $entry->entry_id, // or your receipt logic
                    'collectionOfficer' => $entry->officer->name ?? 'N/A',
                    'duration' => $duration,
                    'location' => $entry->location->location_name ?? 'N/A',
                    'vehicleType' => $entry->vehicleType->type_name ?? 'N/A',
                    'amount' => $amount,
                    'status' => $entry->exit_time ? 'Paid' : 'Unpaid',
                ];
            });

        return response()->json($entries);
    }

    /**
     * Optional: Handle payment for selected entries
     */
    public function paySelected(Request $request)
    {
        $entryIds = $request->get('entry_ids', []);
        if (empty($entryIds)) {
            return back()->with('error', 'No entries selected.');
        }

        $entries = ParkingEntry::whereIn('entry_id', $entryIds)->get();

        $totalAmount = $entries->sum(function ($entry) {
            return $entry->total_amount ?? $entry->rate_per_hour;
        });

        // TODO: implement actual payment logic
        // Example: mark as paid, save to payment history, generate receipt, etc.

        return back()->with('success', "Payment of $totalAmount processed for selected entries.");
    }
}
