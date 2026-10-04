<?php
namespace App\Http\Controllers\Aviation;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class AviationController extends Controller
{
    protected function allow(string $action): void
    {
        abort_unless(auth()->user()?->hasPermission('aviation.'.$action), 403);
    }

    protected function utc(string $value): string
    {
        return Carbon::parse($value, 'UTC')->utc()->format('Y-m-d H:i:s');
    }

    protected function redirectBack(string $message)
    {
        return back()->with('aviation_success', $message);
    }
}
