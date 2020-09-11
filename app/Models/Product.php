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

class Product extends Model
{

    protected $fillable = [
        'name', 'price', 'quantity', 'description', 'attachment', 'provider_id'
    ];
    protected $visible = [
        'id', 'name', 'price', 'quantity', 'description', 'attachment', 'provider_id',
    ];

    static function rules($request)
    {
        foreach ($request['product'] as $pt) {
            
            if (empty($pt['name']) || empty($pt['price']) || empty($pt['quantity']) || empty($pt['description'])) {
                return ["The name,price,quantity,description field is required"];
            }
        }
    }

}
