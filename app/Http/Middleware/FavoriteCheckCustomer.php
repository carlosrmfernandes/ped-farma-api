<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer;
class FavoriteCheckCustomer
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
        
        $customer = Customer::where('user_id', auth()->user()->id)->first();        
        if (auth()->user() && (auth()->user()->id != $customer->user_id)) {
            return response()->json(['data' => 'without permission']);
        }
        return $next($request);        
    }

}
