<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Validator;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Services\Sale\SaleService;
use App\Filters\Sale\SaleFilter;
use App\Models\Provider;

class SaleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $saleFilter = (new SaleFilter())->apply($request->all());
        return response()->json(['data' => $saleFilter]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatorSale = Validator::make($request->all(), Sale::rules());

        if ($validatorSale->fails()) {
            return response()->json(['error' => $validatorSale->errors()]);
        }


        DB::beginTransaction();
        try {
            $providerProduct = Product::where('id', $request->productId)->first();
            if (!$providerProduct) {
                return response()->json(['error' => 'product not found']);
            }
            if ($providerProduct->quantity < $request->quantity) {
                return response()->json(['data' => 'insufficient quantity in stock']);
            }

            $customer = Customer::where('user_id', auth()->user()->id)->first();

            if ($customer) {
                $sale = Sale::create([
                            'customer_id' => $customer->id,
                            'provider_id' => $providerProduct->provider_id,
                            'product_id' => $request->productId,
                            'quantity' => $request->quantity,
                            'form_payment' => $request->formPayment
                ]);
                $product = Product::find($request->productId);
                $product->update([
                    "quantity" => ($product->quantity - $request->quantity),
                ]);
            } else {
                return response()->json(['error' => 'customer not found']);
            }
            //Notificando Fornecedores
            (new SaleService())->setParams($sale)->notification();

            DB::commit();
            return response()->json(['data' => "successful order the supplier was notified by email"], 200);
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
        $sale = Sale::with('product')->where('id', $id)->first();
        if ($sale) {
            $customer = Customer::where('user_id', auth()->user()->id)->first();
            $provider = Provider::where('user_id', auth()->user()->id)->first();

            if ($provider) {
                if ($sale->provider_id != $provider->id) {
                    return response()->json(['data' => 'without permission to view this sale']);
                } else {
                    return response()->json(['data' => $sale], 200);
                }
            } else {
                if ($sale->customer_id != $customer->id) {
                    return response()->json(['data' => 'without permission to view this sale']);
                } else {
                    return response()->json(['data' => $sale], 200);
                }
            }
        } else {
            return response()->json(['data' => 'not found'], 200);
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
        //
    }

}
