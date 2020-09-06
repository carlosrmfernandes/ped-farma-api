<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author carlosfernandes
 */

namespace App\Services\Product;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Sale;

class ProductService
{

    public $products;

    public function setParams($products)
    {
        $this->products = $products;
        return $this;
    }

    public function createOrUpdate()
    {
        $notDelete = [];
        $provider = null;
        $productSave = [];



        if (isset($this->products)) {
            $provider = Provider::where('user_id', auth()->user()->id)->first();

            foreach ($this->products['product'] as $pt) {

                $product = Product::firstOrNew(['id' => $pt['id']]);
                $product->name = $pt['name'];
                $product->price = $pt['price'];
                $product->quantity = $pt['quantity'];
                $product->description = $pt['description'];
                $product->provider_id = $provider->id;
                $product->save();
                $notDelete[] = $product->id;
                $productSave[] = $product;
            }
            
            $a = Sale::whereNotIn('id', $notDelete)->get();
            

            Product::where('provider_id', $provider->id)->whereNotIn('id', $notDelete)->get()->each(function($obj) {
                $sale = Sale::where('product_id', $obj->id)->first();                
                if (!$sale) {
                    $obj->delete();
                }
            });

            return $productSave;
        }
    }

}
