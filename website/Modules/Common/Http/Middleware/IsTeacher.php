<?php

namespace Modules\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsTeacher
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
        // dd($request->user()->profile_type);
        if('teacher' != $request->user()->profile_type){
            if ($request->expectsJson()) {
                $message = "Your are not authorized to access it";
                $responseData = [
                    'status' => false,
                    'message' => $message,
                    'data' => [],
                ];
                return response()->json($responseData, 403, []);
            }
            else{
                return redirect()->route('errors.403');
            }
        }
        return $next($request);
    }
}
