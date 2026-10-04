<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Permission;

class PermissionHelper
{
    public static function hasAccess(User $user, string $permissionSlug): bool
    {
        // Super admin bypasses all checks
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->role && $user->role->hasPermission($permissionSlug);
    }

    public static function hasAnyAccess(User $user, array $permissionSlugs): bool
    {
        // Super admin bypasses all checks
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->role) return false;

        foreach ($permissionSlugs as $slug) {
            if ($user->role->hasPermission($slug)) {
                return true;
            }
        }
        return false;
    }

    public static function getAllPermissionsGrouped(): array
    {
        return config('permissions.modules');
    }

    public static function getPagePermissionMap(): array
    {
        return config('permissions.page_permissions');
    }
}
