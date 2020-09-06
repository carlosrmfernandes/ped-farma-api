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

class FormPayment extends Model
{

    protected $fillable = [
        'name'
    ];
    protected $visible = [
        'id', 'name'
    ];

}
