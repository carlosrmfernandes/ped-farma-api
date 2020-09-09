<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductFilter
 *
 * @author carlosfernandes
 */

namespace App\Filters\Product;

use App\Models\Product;

class ProductFilter
{

    private $query;

    public function apply($request)
    {
       
        $this->query = Product::query();
        if (!empty($request['searchQuery'])) {
            $this->query = Product::where('name', 'ilike', '%' . $request['searchQuery'] . '%');
        }
        
        return $this->query->get();
    }

}
