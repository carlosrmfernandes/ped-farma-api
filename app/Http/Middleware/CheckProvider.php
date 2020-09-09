<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Provider;

class CheckProvider
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
        
        if ($provider) {
            if (auth()->user() && (auth()->user()->id != $provider->user_id)) {
                return response()->json(['data' => 'without permission']);
            }
        } else {
            return response()->json(['error' => 'Provider not found']);
        }

        return $next($request);
    }

}
