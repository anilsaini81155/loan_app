<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Helpers\GlobalsHelper;
use App\Models;
use Closure;


class LoamAuthCheck
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

        $sysData = \App\Models\Token::where('token', $request['token'])
            ->first();

        if ($sysData === false) {
            return GlobalsHelper\putsResponse(false, "Invalid Token Provided", [], 400);
        }

        $sysData->created_at;

        $lastTime = new \DateTime(now());
        $diffTime = $lastTime->diff(new \DateTime($sysData->created_at));

        if ($diffTime->i >  config('commonconfig.timeoutInMins')) {
            return GlobalsHelper\putsResponse(false, "Token TimeOut", [], 400);
        }

        return $next($request);
    }
}
