<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditTrailController extends BaseController
{
    public function index(Request $request)
    {
        $query = AuditTrail::with('user.staff')->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', "%{$request->search}%")
                    ->orWhere('model_type', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('username', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->has('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        $auditTrails = $query->paginate(25);

        return $this->successResponse(["content" => $auditTrails, "model_type" => AuditTrail::distinct('model_type')->pluck('model_type')], "Audit Trails retrieved successfully");
    }
}
