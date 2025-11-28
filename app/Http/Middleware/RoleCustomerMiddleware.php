<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class RoleCustomerMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!Auth::check()) {
			return response()->json(['error' => 'Unauthenticated'], 401);
		}

		if (Auth::user()->role !== 'customer') {
			return response()->json(['error' => 'Forbidden'], 403);
		}

		return $next($request);
	}

}
