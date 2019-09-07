<?php

namespace App\Http\Middleware;

use Closure;
use App\PluginRequest;
class LimitRequestAPI
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
        if($request->ip)
        {
            $ip=$request->ip;
        }
        else
        {
            $ip=request()->ip();
        }
        if($pr=PluginRequest::where('ip',$ip)->first())
        {
            if($pr->count==10)
            {
                $errors=array(
                    'Limit Reached'=>['Too Many Requests.']
                );
                return response()->json(["errors"=>$errors],422);
            }
            $pr->count=$pr->count+1;
            $pr->save();
        }
        else
        {
            $pr=new PluginRequest();
            $pr->ip=$ip;
            $pr->count=1;
            $pr->save();
        }
        return $next($request);
    }
}
