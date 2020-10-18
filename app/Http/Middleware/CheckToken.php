<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Appuser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->token;
        $user = Appuser::firstWhere('token', $token);
        if (is_null($user)) {
            return sendError('Empty token', 'Empty token', 404);
        }

        return $next($request);
    }
}
