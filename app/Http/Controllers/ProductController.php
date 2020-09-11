<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Validator;
use App\Services\Product\ProductService;
use App\Filters\Product\ProductFilter;
use App\Models\Sale;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = (new ProductFilter())->apply($request->all());
        return response()->json(['data' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatorProduct = Product::rules($request->all());

        if (!empty($validatorProduct)) {
            return response()->json(['erro' => $validatorProduct]);
        }
        $product = (new ProductService())->setParams($request->all())->createOrUpdate();
        return response()->json(['data', $product], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['data' => 'product not found']);
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
        $validator = Validator::make($request->all(), Product::rules());

        if ($validator->fails()) {
            return response()->json(['erro' => $validator->errors()]);
        }

        $product = (new ProductService())->setParams($request->all())->createOrUpdate();
        return response()->json(['data', $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = null;
        $sale = Sale::where('product_id', $id)->first();
        if (!$sale) {
            $product = Product::where('id', $id)->first();
            if ($product) {
                $data = "product successfully removed";
                $product = Product::where('id', $id)->delete();
            } else {
                $data = "product not found";
            }
        } else {
            $data = "This product is part of the sales history so it cannot be removed";
        }
        return response()->json(['data' => $data], 200);
    }

}
