<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer;

class CheckCustomer
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
        if ($customer) {
            if (auth()->user() && (auth()->user()->id != $customer->user_id)) {
                return response()->json(['data' => 'without permission']);
            }
        } else {
            return response()->json(['error' => 'Customer not found']);
        }


        return $next($request);
    }

}
