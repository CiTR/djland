<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
  /**
   * The Guard implementation.
   *
   * @var Guard
   */
  protected $auth;

  /**
   * Create a new filter instance.
   *
   * @param  Guard  $auth
   * @return void
   */
  public function __construct(Guard $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/headers/session_header.php");
    if (!(isset($_SESSION['sv_username']) && isset($_SESSION['sv_id']))) {
      return response('Unauthorized.', 401);
    }
    return $next($request);
  }
}
