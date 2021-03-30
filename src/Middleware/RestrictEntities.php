<?php

namespace Geekyants\Sharedo\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictEntities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!empty(config('sharedo.restrict-entities'))) {
            $entity = $request->route()->parameters()['entity'];
            if (!in_array($entity, config('sharedo.restrict-entities'))) {
                return back()->withErrors("Entity Restricted!");
            }
            return $next($request);
        }

        return $next($request);
    }
}
