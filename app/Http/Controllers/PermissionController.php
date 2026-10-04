<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class PermissionController extends BaseController
{
    public function index(Request $request)
    {
        $query = Permission::with('roles')->latest();

        if ($request->has('module')) {
            $query->where('module', $request->module);
        }

        $permissions = $query->get();
        return $this->successResponse($permissions, 'Permissions retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:permissions',
            'slug' => 'required|string|max:100|unique:permissions',
            'module' => 'required|string|max:50',
            'description' => 'nullable|string|max:200',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        $permission = Permission::create($validator->validated());
        return $this->successResponse($permission, 'Permission created successfully', 201);
    }

    public function show(Permission $permission)
    {
        return $this->successResponse($permission->load('roles'), 'Permission retrieved successfully');
    }

    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100|unique:permissions,name,'.$permission->id,
            'slug' => 'string|max:100|unique:permissions,slug,'.$permission->id,
            'module' => 'string|max:50',
            'description' => 'nullable|string|max:200',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        $permission->update($validator->validated());
        return $this->successResponse($permission, 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->successResponse(null, 'Permission deleted successfully');
    }

    public function modules()
    {
        $modules = Permission::distinct()->orderBy('module')->pluck('module');
        return $this->successResponse($modules, 'Modules retrieved successfully');
    }

    public function assignToRoles(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation error');
        }

        $permission->roles()->sync($request->roles);
        return $this->successResponse($permission->load('roles'), 'Permission assigned to roles successfully');
    }

    public function getAssignedRoles(Permission $permission)
    {
        return $this->successResponse([
            'assigned' => $permission->roles,
            'available' => Role::whereDoesntHave('permissions', function($q) use ($permission) {
                $q->where('permission_id', $permission->id);
            })->get()
        ], 'Role assignment data retrieved successfully');
    }
}
