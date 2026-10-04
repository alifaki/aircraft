<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class AviationAjax
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (!$request->expectsJson() || $request->isMethod('GET') || !$response instanceof RedirectResponse) return $response;
        $errors = $request->session()->get('errors');
        if ($errors && $errors->any()) {
            $messages = $errors->getBag('default')->messages();
            $request->session()->forget(['errors', '_old_input']);
            return response()->json(['message'=>'Please check the highlighted fields.', 'errors'=>$messages], 422);
        }
        $warning = $request->session()->pull('aviation_warning');
        $message = $request->session()->pull('aviation_success');
        if (!$message && !$warning) return response()->json(['message'=>'Your session has changed. Sign in again.'], 401);
        return response()->json(['message'=>$warning ?: $message, 'level'=>$warning ? 'warning' : 'success']);
    }
}
