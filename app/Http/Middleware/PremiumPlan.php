<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
        if(!isAdministrator())
        {
            $user=AdminUser::with('business')->find(auth()->user()->id);
            if($user->business->plan=='free')
            {
               return response(
                "Only premium users can access admin panel. Buy our plan and try again. https://eatinsta.callvcal.com/"
               ,401);
                return view('plan_expired');
            }
        }
        

        return $next($request);
    }
}
