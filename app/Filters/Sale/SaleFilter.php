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

namespace App\Filters\sale;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Provider;

class SaleFilter
{

    private $query;
    private $searchQuery;
    private $user;

    public function apply($request)
    {
                
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        $provider = Provider::where('user_id', auth()->user()->id)->first();

        if ($customer) {
            $this->user = $customer->id;
        } else {
            $this->user = $provider->id;
        }

        $this->query = Sale::query();
        if (!empty($request['searchQuery'])) {
            $this->searchQuery = $request['searchQuery'];
            if ($customer) {
                $this->query = Sale::whereHas('product', function ($query) {
                            $query->where('name', 'ilike', '%' . $this->searchQuery . '%');
                        })->where('customer_id', $this->user);
            } else {
                $this->query = Sale::whereHas('product', function ($query) {
                            $query->where('name', 'ilike', '%' . $this->searchQuery . '%');
                        })->where('provider_id', $this->user);
            }
        }
        if ($customer) {
            $this->query->where('customer_id', $this->user);
        } else {            
            $this->query->where('provider_id', $this->user);
        }
        return $this->query->with('product')->get();
    }

}
