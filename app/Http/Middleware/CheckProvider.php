<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Provider;
use App\Models\Product;

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
        if ($request->id) {
            $product = Product::where('id', $request->id)->first();
            if ($product) {
                if ($product->provider_id != $provider->id) {
                    return response()->json(['data' => 'without permission to remove this product']);
                }
            }
        }

        return $next($request);
    }

}
