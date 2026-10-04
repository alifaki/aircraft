<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Permission;

class PageController extends Controller
{
    private $directories = [
        "pages", "reports", "parking"
    ];
    public function show($page)
    {
        // Check if user has permission through their role
        if (!auth()->user()->hasPermission("url:".$page)) {
            abort(403, 'Unauthorized access');
        }

        // Loop through each directory to find where the view exists
        foreach ($this->directories as $dir) {
            if (View::exists($dir . '.' . $page)) {
                return view($dir . '.' . $page);
            }
        }

        // If view not found in any directory
        abort(404);
    }
}
