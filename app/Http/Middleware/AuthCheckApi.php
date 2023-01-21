<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class AuthCheckApi
{

    public function handle(Request $request, Closure $next,$guard = null)
    {

        if($guard != null){

            auth()->shouldUse($guard);

            try {

                 auth()->authenticate();

            }catch (\Exception $exception){


                return response()->json(['code' => 401 , 'message' => $exception->getMessage()]);
            }

            return $next($request);

        }




    }
}
