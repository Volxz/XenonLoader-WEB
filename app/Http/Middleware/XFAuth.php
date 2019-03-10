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
        if(!$request->has("user") || !$request->has("password")){
            abort(403);
        }
        $xfuser = XFUser::where("username", "=", $request->get("user"))->get()->first();
        $correctPass = $xfuser->checkPassword($request->get("password"));
        if(!$correctPass) {
            abort(403);
        } else {
            return $next($request);
        }
    }
}
