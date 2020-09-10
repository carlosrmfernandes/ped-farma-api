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
        return response()->json(['data'=> $saleFilter]);
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
            dd($providerProduct->quantity);
            if ($providerProduct->quantity < $request->quantity) {
                return response()->json(['data' => 'insufficient quantity in stock']);
            }
            if (!$providerProduct) {
                return response()->json(['error' => 'product not found']);
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
            return response()->json(['data' => "successful order"], 200);
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
