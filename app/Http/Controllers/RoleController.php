<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->successResponse($roles, 'Roles retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles',
            'description' => 'nullable|string|max:200',
            'is_default' => 'boolean',
            'status' => 'string|max:20|in:active,inactive',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        try {
            DB::beginTransaction();

            $role = Role::create($request->except('permissions'));

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            DB::commit();
            return $this->successResponse($role->load('permissions'), 'Role created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 'Role creation failed', 500);
        }
    }

    public function show(Role $role)
    {
        return $this->successResponse($role->load('permissions'), 'Role retrieved successfully');
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:100|unique:roles,name,'.$role->id,
            'description' => 'nullable|string|max:200',
            'is_default' => 'boolean',
            'status' => 'string|max:20|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        $role->update($validator->validated());
        return $this->successResponse($role->load('permissions'), 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return $this->errorResponse('Conflict', 'Cannot delete role with assigned users' , 409);
        }

        $role->delete();
        return $this->successResponse(null, 'Role deleted successfully');
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(Arr::map($validator->errors()->toArray(), fn ($error) => $error[0]), 'Validation error');
        }

        $role->permissions()->sync($request->permissions);
        return $this->successResponse($role->load('permissions'), 'Permissions assigned successfully');
    }

    public function availablePermissions(Role $role)
    {
        $assignedPermissionIds = $role->permissions()->pluck('permissions.id');
        $availablePermissions = Permission::whereNotIn('id', $assignedPermissionIds)
            ->orderBy('module')
            ->get()
            ->groupBy('module');

        return $this->successResponse($availablePermissions, 'Available permissions retrieved successfully');
    }

    // Add to your RoleController
    public function removePermissions(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 'Validation error');
        }

        $role->permissions()->detach($request->permissions);

        return $this->successResponse(
            $role->load('permissions'),
            'Permissions removed successfully'
        );
    }
}
