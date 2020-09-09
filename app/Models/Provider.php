<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Provider
 *
 * @author carlosfernandes
 */
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{

    protected $fillable = [
        'name', 'phone', 'number', 'city', 'cnpj', 'user_id','address'
    ];
    protected $visible = [
        'id','name', 'phone', 'number', 'city', 'cnpj', 'user_id','address'
    ];

    static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|max:255',
            'cnpj' => 'required|string|max:255|unique:providers,cnpj' . ($id == null ? '' : ',' . $id),
        ];
    }

}
