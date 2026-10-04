<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\IpRestriction;

class IpRestrictionMiddleware
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();

        // Check blacklist
        $blacklisted = IpRestriction::where('type', 'blacklist')
            ->where('is_active', true)
            ->where('ip_address', $ip)
            ->exists();

        if ($blacklisted) {
            abort(403, 'Your IP address has been blocked');
        }

        // Check whitelist (if whitelist is enabled)
        if (config('ip-restriction.whitelist_enabled')) {
            $whitelisted = IpRestriction::where('type', 'whitelist')
                ->where('is_active', true)
                ->where(function($query) use ($ip) {
                    $query->where('ip_address', $ip)
                        ->orWhere('ip_address', '*');
                })
                ->exists();

            if (!$whitelisted) {
                abort(403, 'Access denied from your IP address');
            }
        }

        return $next($request);
    }
}
