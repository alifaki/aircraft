<?php

return [
    'modules' => [
        'dashboard' => [
            'view-dashboard' => 'View Dashboard',
        ],
        'bill-management' => [
            'view-bills' => 'View Bills',
            'manage-bills' => 'Manage Bills',
            'view-reconciliation' => 'View Reconciliation',
            'manage-reconciliation' => 'Manage Reconciliation',
            'manage-bill-structure' => 'Manage Bill Structure',
            'manage-bill-group' => 'Manage Bill Group',
        ],
        // Add all other modules and permissions here
    ],

    'page_permissions' => [
        'home' => 'view-dashboard',
        'bills' => 'view-bills',
        'reconciliation' => 'view-reconciliation',
        'bill-structure' => 'manage-bill-structure',
        'bill-group' => 'manage-bill-group',
        // Map all other pages to their required permissions
    ],
];
