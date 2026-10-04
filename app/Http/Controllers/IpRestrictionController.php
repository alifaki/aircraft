<?php
// app/Http/Controllers/IpRestrictionController.php
namespace App\Http\Controllers;

use App\Models\IpRestriction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class IpRestrictionController extends BaseController
{
    public function index(Request $request)
    {
        $query = IpRestriction::with('creator.staff')->latest();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('ip_address', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $ipRestrictions = $query->paginate(25);

        return $this->successResponse($ipRestrictions,'Branches retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|ip:unique:ip_restrictions,ip_address',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:whitelist,blacklist',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]),
                'Validation error'
            );
        }

        $ipRestriction = IpRestriction::create([
            'ip_address' => $request->ip_address,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => $request->is_active ?? true,
            'created_by' => auth()->id()
        ]);

        return $this->successResponse($ipRestriction, 'IP manager created successfully', 201);
    }

    public function update(Request $request, IpRestriction $ipRestriction)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|ip:|unique:ip_restrictions,ip_address,'.$ipRestriction->id,
            'description' => 'nullable|string|max:255',
            'type' => 'sometimes|in:whitelist,blacklist',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]),
                'Validation error'
            );
        }

        $ipRestriction->update($request->only([
            'ip_address', 'description', 'type', 'is_active'
        ]));

        return $this->successResponse($ipRestriction, 'IP manager updated successfully', 201);
    }

    public function destroy(IpRestriction $ipRestriction)
    {
        $ipRestriction->delete();
        return $this->errorResponse(null, "Deleted successfully");
    }
}
