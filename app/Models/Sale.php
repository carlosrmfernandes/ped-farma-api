<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Category
 *
 * @author carlosfernandes
 */
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

    protected $fillable = [
        'customer_id', 'provider_id', 'product_id', 'quantity', 'form_payment'
    ];
    protected $visible = [
        'id','quantity', 'form_payment','product',
    ];

    static function rules($id = null)
    {
        return [
            'productId' => 'required',
            'quantity' => 'required',
            'formPayment' => 'required',
        ];
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'id','product_id');
    }

}
