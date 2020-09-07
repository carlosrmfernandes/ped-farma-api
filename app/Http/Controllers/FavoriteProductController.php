<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoriteProduct;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class FavoriteProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatorFavoriteProduct = Validator::make($request->all(), Sale::rules());

        if ($validatorFavoriteProduct->fails()) {
            return response()->json(['error' => $validatorFavoriteProduct->errors()]);
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
        //
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
        FavoriteProduct::where('id', $id)->delete();
        return response()->json(['data' => 'Favorite product removed successfully'], 200);
    }

}
