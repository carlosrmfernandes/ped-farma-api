<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Provider;
class ProviderProduct
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
        $provider = Provider::where('user_id', auth()->user()->id)->first();         
        if (auth()->user() && (auth()->user()->id != $provider->id)) {
            return response()->json(['data' => 'without permission']);
        }
        return $next($request);
    }
}
