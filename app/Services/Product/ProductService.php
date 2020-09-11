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
use App\Models\FavoriteProduct;
use Storage;

class ProductService
{

    public $products;
    public $infoDelet;

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
        $notUpdateProduct = [];


        if (isset($this->products)) {
            $provider = Provider::where('user_id', auth()->user()->id)->first();
            foreach ($this->products['product'] as $pt) {
                if (empty($pt['id']) ? null : $pt['id']) {
                    $pd = Product::where('id', $pt['id'])->get();
                    if ($pd) {
                        foreach ($pd as $p) {
                            if ($p->provider_id != $provider->id) {
                                $notUpdateProduct[] = $pt;
                            } else {
                                $attachment = null;
                                if (isset($pt['attachment'])) {
                                    $attachment = $pt['attachment']->store('imagens', 'public');
                                }
                                $product = Product::firstOrNew(['id' => empty($pt['id']) ? null : $pt['id']]);
                                Storage::disk('public')->delete($product->attachment);
                                $product->name = $pt['name'];
                                $product->price = $pt['price'];
                                $product->quantity = $pt['quantity'];
                                $product->description = $pt['description'];
                                $product->attachment = $attachment;
                                $product->provider_id = $provider->id;
                                $product->save();
                                $notDelete[] = $product->id;
                                $productSave[] = $product;
                            }
                        }
                    }
                } else {
                    $attachment = null;
                    if (isset($pt['attachment'])) {
                        $attachment = $pt['attachment']->store('imagens', 'public');
                    }
                    $product = Product::firstOrNew(['id' => empty($pt['id']) ? null : $pt['id']]);
                    Storage::disk('public')->delete($product->attachment);
                    $product->name = $pt['name'];
                    $product->price = $pt['price'];
                    $product->quantity = $pt['quantity'];
                    $product->description = $pt['description'];
                    $product->attachment = $attachment;
                    $product->provider_id = $provider->id;
                    $product->save();
                    $notDelete[] = $product->id;
                    $productSave[] = $product;
                }
            }


            Product::where('provider_id', $provider->id)->whereNotIn('id', $notDelete)->get()->each(function($obj) {
                $sale = Sale::where('product_id', $obj->id)->first();
                FavoriteProduct::where('product_id', $obj->id)->delete();

                if (!$sale) {
                    Storage::disk('public')->delete($obj->attachment);
                    $obj->delete();
                } else {
                    $this->infoDelet['infoDelete'] = "these products cannot be removed because they are part of the sales history";
                    $this->infoDelet[] = $obj;
                }
            });
            
            if ($notUpdateProduct) {
                $productSave['notUpdateProduct'] = "these products does not belong to the informed supplier";
                $productSave[] = $notUpdateProduct;
            }
            if($this->infoDelet){
                $productSave[] = $this->infoDelet;
            }
            return $productSave;
        }
    }

}
