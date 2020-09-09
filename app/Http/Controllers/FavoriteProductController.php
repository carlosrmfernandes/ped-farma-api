<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoriteProduct;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Product;
use App\Filters\Favorite\FavoriteFilter;
use Validator;

class FavoriteProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $favorite = (new FavoriteFilter())->apply($request->all());
        return response()->json(['data' => $favorite]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::where('id', $request->productId)->first();
        if (!$product) {
            return response()->json(['error' => 'product not found']);
        }
        $validatorFavoriteProduct = Validator::make($request->all(), FavoriteProduct::rules());
        if ($validatorFavoriteProduct->fails()) {
            return response()->json(['error' => $validatorFavoriteProduct->errors()]);
        }

        if (FavoriteProduct::alreadyFavoredProduct($request->productId)) {
            return response()->json(['data' => 'already favored product'], 200);
        }

        DB::beginTransaction();
        try {
            $customer = Customer::where('user_id', auth()->user()->id)->first();
            FavoriteProduct::create([
                "product_id" => $request->productId,
                "customer_id" => $customer->id,
            ]);
            DB::commit();
            return response()->json(['data' => 'successfully'], 200);
        } catch (Exception $ex) {
            return response()->json(['data' => $ex->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $favoriteProduct = FavoriteProduct::with('product')->find($id);
        if ($favoriteProduct) {
            return response()->json($favoriteProduct);
        } else {
            return response()->json(['data' => 'favorite product not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        if ($customer && ($customer->user_id == auth()->user()->id)) {
            $favoriteProduct = FavoriteProduct::where('id', $id)->first();
            if ($favoriteProduct) {
                FavoriteProduct::where('id', $id)->delete();
                return response()->json(['data' => 'Favorite product removed successfully'], 200);
            } else {
                return response()->json(['error' => 'Favorite product not found']);
            }
        } else {
            return response()->json(['data' => 'without permission']);
        }
    }

}
