<?php

namespace App\Http\Middleware;

use Closure;
use \App\Models\XFUser;

class XFAuth
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
        if(ipBlocked($request->ip())){
            incrementFailedIP($request->ip());
            abort(429);
        }
        if(!$request->has("username") || !$request->has("password")) {
            incrementFailedIP($request->ip());
            abort(403);
        }

        $xfuser = XFUser::where("username", "=", $request->get("username"))->get()->first();
        $correctPass = $xfuser->checkPassword($request->get("password"));
        if(!$correctPass) {
            incrementFailedIP($request->ip());

            abort(403);
        } else {
            return $next($request);
        }
    }
}
