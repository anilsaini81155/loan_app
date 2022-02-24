<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Helpers\GlobalsHelper;
use App\Models;
use Closure;


class LoanAuthCheck
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

        $lastTime = new \DateTime(now());
        $diffTime = $lastTime->diff(new \DateTime($sysData->created_at));

        if ($diffTime->i >  config('commonconfig.timeoutInMins')) {
            return GlobalsHelper\putsResponse(false, "Token TimeOut , Please follow step1", [], 400);
        }

        $sysData = \App\Models\User::where(['mobile_no' => $sysData->mobile_no, 'name' => $sysData->name])
            ->first();
        
        $request->merge(['user_id' => $sysData->id]);
        
        return $next($request);
    }
}
