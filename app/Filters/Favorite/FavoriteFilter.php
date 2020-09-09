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

namespace App\Filters\Favorite;

use App\Models\Customer;
use App\Models\FavoriteProduct;

class FavoriteFilter
{

    private $query;
    private $searchQuery;

    public function apply($request)
    {

        $customer = Customer::where('user_id', auth()->user()->id)->first();

        if ($customer) {
            $this->user = $customer->id;
        }

        $this->query = FavoriteProduct::query();
        if (!empty($request['searchQuery'])) {
            $this->searchQuery = $request['searchQuery'];

            $this->query = FavoriteProduct::whereHas('product', function ($query) {
                        $query->where('name', 'ilike', '%' . $this->searchQuery . '%');
                    })->where('customer_id', $this->user);
        }
        $this->query->where('customer_id', $this->user);
        return $this->query->with('product')->get();
    }

}
