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

class Customer extends Model
{

    protected $fillable = [
        'name', 'phone', 'address', 'number', 'city','active', 'user_id'
    ];
    protected $visible = [
        'id','name', 'phone', 'address', 'number', 'city','active', 'user_id'
    ];
    
    static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'string|max:15',
            'address' => 'string|max:255',
            'number' => 'integer',
            'city' => 'string|max:255',            
            'phone' => 'required|string|max:255',
        ];
    }


}
