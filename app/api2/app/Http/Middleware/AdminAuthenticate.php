<?php

namespace App\Http\Middleware;

use Closure;
use App\Member;

class AdminAuthenticate
{
    /**
     * Handle an incoming request, and block it if not a staff member or admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		return app(Authenticate::class)->handle($request, function ($request) use ($next) {
			$member = Member::find($_SESSION['sv_id']);
			if($member->isAdmin()) return $next($request);
			else return response('You are not an authorized admin member.',401);
		});
    }
}
