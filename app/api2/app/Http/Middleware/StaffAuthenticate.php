<?php

namespace App\Http\Middleware;

use Closure;
use App\Member;

class StaffAuthenticate
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
		return app(Authenticate::class)->handle($request, function ($request) use ($next) {
			$member = Member::find($_SESSION['sv_id']);
			$permission = $member->user->permission;
			if($member->member_type == 'Staff' || $permission['operator'] == 1 || $permission['administrator'] == 1) return $next($request);
			else return response('You are not an authorized staff member.',401);
		});
    }
}
