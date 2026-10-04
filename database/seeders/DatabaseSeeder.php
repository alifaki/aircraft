<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'User Management'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'User Management'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'User Management'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'User Management'],

            // Role Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'Role Management'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'module' => 'Role Management'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'module' => 'Role Management'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'module' => 'Role Management'],

            // Permission Management
            ['name' => 'View Permissions', 'slug' => 'permissions.view', 'module' => 'Permission Management'],
            ['name' => 'Assign Permissions', 'slug' => 'permissions.assign', 'module' => 'Permission Management'],

            // Staff Management
            ['name' => 'View Staff', 'slug' => 'staff.view', 'module' => 'Staff Management'],
            ['name' => 'Create Staff', 'slug' => 'staff.create', 'module' => 'Staff Management'],
            ['name' => 'Edit Staff', 'slug' => 'staff.edit', 'module' => 'Staff Management'],
            ['name' => 'Delete Staff', 'slug' => 'staff.delete', 'module' => 'Staff Management'],

            // Company Management
            ['name' => 'Manage Company', 'slug' => 'company.manage', 'module' => 'Company Management'],

            // Branch Management
            ['name' => 'View Branches', 'slug' => 'branches.view', 'module' => 'Branch Management'],
            ['name' => 'Create Branches', 'slug' => 'branches.create', 'module' => 'Branch Management'],
            ['name' => 'Edit Branches', 'slug' => 'branches.edit', 'module' => 'Branch Management'],
            ['name' => 'Delete Branches', 'slug' => 'branches.delete', 'module' => 'Branch Management'],

            // Department Management
            ['name' => 'View Departments', 'slug' => 'departments.view', 'module' => 'Department Management'],
            ['name' => 'Create Departments', 'slug' => 'departments.create', 'module' => 'Department Management'],
            ['name' => 'Edit Departments', 'slug' => 'departments.edit', 'module' => 'Department Management'],
            ['name' => 'Delete Departments', 'slug' => 'departments.delete', 'module' => 'Department Management'],

            // Section Management
            ['name' => 'View Sections', 'slug' => 'sections.view', 'module' => 'Section Management'],
            ['name' => 'Create Sections', 'slug' => 'sections.create', 'module' => 'Section Management'],
            ['name' => 'Edit Sections', 'slug' => 'sections.edit', 'module' => 'Section Management'],
            ['name' => 'Delete Sections', 'slug' => 'sections.delete', 'module' => 'Section Management'],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::create($permission);
        }

        // Roles
        $adminRole = \App\Models\Role::create([
            'name' => 'Administrator',
            'description' => 'Has full access to all system features',
            'is_default' => false,
            'status' => 'active'
        ]);

        $managerRole = \App\Models\Role::create([
            'name' => 'Manager',
            'description' => 'Can manage staff and departments',
            'is_default' => false,
            'status' => 'active'
        ]);

        $userRole = \App\Models\Role::create([
            'name' => 'User',
            'description' => 'Basic user with limited access',
            'is_default' => true,
            'status' => 'active'
        ]);

        // Assign all permissions to admin role
        $allPermissions = \App\Models\Permission::all();
        $adminRole->permissions()->attach($allPermissions);

        // Assign manager permissions
        $managerPermissions = \App\Models\Permission::whereIn('slug', [
            'staff.view',
            'staff.create',
            'staff.edit',
            'departments.view',
            'departments.edit',
            'sections.view',
            'sections.edit',
            'branches.view'
        ])->get();
        $managerRole->permissions()->attach($managerPermissions);

        // Assign basic user permissions
        $userPermissions = \App\Models\Permission::whereIn('slug', [
            'staff.view',
            'departments.view',
            'sections.view'
        ])->get();
        $userRole->permissions()->attach($userPermissions);

        // Companies
        $company = \App\Models\Company::create([
            'app_id' => 'COMP' . now()->format('YmdHis'),
            'company_name' => 'Default Company',
            'description' => 'Main company for the system',
            'logo' => null,
            'location' => 'Main Street',
            'city' => 'Metropolis',
            'country' => 'United States',
            'email' => 'info@defaultcompany.com',
            'phone' => '+1234567890',
            'postal_address' => 'P.O. Box 1234',
            'website' => 'www.defaultcompany.com',
            'status' => 'active',
            'donation' => 0.00
        ]);

        // Branches
        $branch = \App\Models\Branch::create([
            'company_id' => $company->id,
            'branch_name' => 'Headquarters',
            'branch_code' => 'HQ' . now()->format('Ymd'),
            'location' => 'Main Street',
            'city' => 'Metropolis',
            'country' => 'United States',
            'manager_name' => 'John Doe',
            'manager_phone' => '+1234567891',
            'manager_email' => 'manager@defaultcompany.com',
            'status' => 'active'
        ]);

        // Departments
        $adminDept = \App\Models\Department::create([
            'name' => 'Administration',
            'code' => 'ADM',
            'description' => 'Main administrative department',
            'head_ofd_id' => null,
            'is_active' => true
        ]);

        $hrDept = \App\Models\Department::create([
            'name' => 'Human Resources',
            'code' => 'HR',
            'description' => 'Human Resources department',
            'head_ofd_id' => null,
            'is_active' => true
        ]);

        $itDept = \App\Models\Department::create([
            'name' => 'Information Technology',
            'code' => 'IT',
            'description' => 'IT Support and Development',
            'head_ofd_id' => null,
            'is_active' => true
        ]);

        // Sections
        $execSection = \App\Models\Section::create([
            'department_id' => $adminDept->id,
            'name' => 'Executive Office',
            'code' => 'ADM-EXEC',
            'description' => 'Executive management section',
            'head_id' => null,
            'is_active' => true
        ]);

        $devSection = \App\Models\Section::create([
            'department_id' => $itDept->id,
            'name' => 'Software Development',
            'code' => 'IT-DEV',
            'description' => 'Software development team',
            'head_id' => null,
            'is_active' => true
        ]);

        // Staff
        $adminStaff = \App\Models\Staff::create([
            'branch_id' => $branch->id,
            'section_id' => $execSection->id,
            'employee_number' => 'EMP' . now()->format('Ymd') . '001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@defaultcompany.com',
            'phone' => '+1234567892',
            'position' => 'System Administrator',
            'status' => 'active',
            'date_of_commencement' => now()->subYear()
        ]);

        $hrStaff = \App\Models\Staff::create([
            'branch_id' => $branch->id,
            'section_id' => $execSection->id,
            'employee_number' => 'EMP' . now()->format('Ymd') . '002',
            'first_name' => 'HR',
            'last_name' => 'Manager',
            'email' => 'hr@defaultcompany.com',
            'phone' => '+1234567893',
            'position' => 'Human Resources Manager',
            'status' => 'active',
            'date_of_commencement' => now()->subMonths(6)
        ]);

        // Update department heads
        $adminDept->update(['head_ofd_id' => $adminStaff->id]);
        $hrDept->update(['head_ofd_id' => $hrStaff->id]);

        // Users
        $bootstrapPassword = env('AVIATION_ADMIN_PASSWORD') ?: Str::password(24);
        if (!env('AVIATION_ADMIN_PASSWORD')) $this->command?->warn('One-time admin password: '.$bootstrapPassword.' (save this now).');
        \App\Models\User::create([
            'username' => 'admin',
            'staff_id' => $adminStaff->id,
            'password' => Hash::make($bootstrapPassword),
            'status' => 'active',
            'type' => 'staff',
            'role_id' => $adminRole->id
        ]);

        $managerPassword = env('AVIATION_MANAGER_PASSWORD') ?: Str::password(24);
        if (!env('AVIATION_MANAGER_PASSWORD')) $this->command?->warn('One-time hrmanager password: '.$managerPassword.' (save this now).');
        \App\Models\User::create([
            'username' => 'hrmanager',
            'staff_id' => $hrStaff->id,
            'password' => Hash::make($managerPassword),
            'status' => 'active',
            'type' => 'staff',
            'role_id' => $managerRole->id
        ]);

        $this->call(AviationSeeder::class);
    }
}
