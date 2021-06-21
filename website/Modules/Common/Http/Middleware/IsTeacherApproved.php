<?php

namespace Modules\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsTeacherApproved
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
        if(null == $request->user()->profile->approver_id){
            if ($request->expectsJson()) {
                $message = "Please wait while admin approves you account";
                $responseData = [
                    'status' => false,
                    'message' => $message,
                    'data' => [],
                ];
                return response()->json($responseData, 202, []);
            }
            else{
                return redirect()->route('errors.202');
            }
        }
        return $next($request);
    }
}
