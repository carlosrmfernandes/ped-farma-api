<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Product
 *
 * @author carlosfernandes
 */
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model
{

    protected $fillable = [
        'product_id', 'customer_id'
    ];
    protected $visible = [
        'product_id', 'customer_id'
    ];

    static function rules($id = null)
    {
        return [
            'productId' => 'required',
        ];
    }

    static function alreadyFavoredProduct($roductId)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        $favoriteProduct = FavoriteProduct::where('customer_id', $customer->id)->where('product_id', $roductId)->first();
        return $favoriteProduct;
    }

}
