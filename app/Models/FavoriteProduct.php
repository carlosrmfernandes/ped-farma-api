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
            'product_id' => 'required',            
        ];
    }

}
