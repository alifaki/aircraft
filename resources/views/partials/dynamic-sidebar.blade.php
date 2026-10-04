@php use App\Helpers\PermissionHelper; @endphp
<nav class="sidebar-nav">
    <ul id="sidebarnav" class="mb-0">
        @foreach(PermissionHelper::getAllPermissionsGrouped() as $module => $permissions)
            @php
                $modulePermissions = array_keys($permissions);
                $hasModuleAccess = PermissionHelper::hasAnyAccess(auth()->user(), $modulePermissions);
            @endphp

            @if($hasModuleAccess)
                <li class="sidebar-item" id="{{ str_replace(' ', '-', strtolower($module)) }}">
                    @if(count($permissions) > 1)
                        <a class="sidebar-link has-arrow info-hover-bg" href="javascript:void(0)" aria-expanded="false">
                            <span class="aside-icon p-2 bg-indigo-subtle rounded-1 ti ti-{{ $moduleIconMap[$module] ?? 'circle' }}"></span>
                            <span class="hide-menu ps-1">{{ ucfirst($module) }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            @foreach($permissions as $permissionSlug => $permissionName)
                                @if(PermissionHelper::hasAccess(auth()->user(), $permissionSlug))
                                    @php
                                        $route = self::getRouteForPermission($permissionSlug);
                                    @endphp
                                    @if($route)
                                        <li class="sidebar-item">
                                            <a href="{{ route($route) }}" class="sidebar-link">
                                                <span class="sidebar-icon"></span>
                                                <span class="hide-menu">{{ $permissionName }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    @else
                        @php
                            $permissionSlug = array_key_first($permissions);
                            $route = self::getRouteForPermission($permissionSlug);
                        @endphp
                        @if($route)
                            <a class="sidebar-link no-arrow success-hover-bg" href="{{ route($route) }}">
                                <span class="aside-icon p-2 bg-indigo-subtle rounded-1 ti ti-{{ $moduleIconMap[$module] ?? 'circle' }}"></span>
                                <span class="hide-menu ps-1">{{ ucfirst($module) }}</span>
                            </a>
                        @endif
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
</nav>
