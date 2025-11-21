<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request?->user();
        if (!empty($user) && $user->user_type == EVENT_ORGANIZER) {
            if ($user->status == 'suspended') {
                return response()->json([
                    "status" => false,
                    "message" => "Your account is suspended. Contact to adminstrator"
                ], 401);
            }
        }
        return $next($request);
    }
}
