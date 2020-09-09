<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaleService
 *
 * @author carlosfernandes
 */

namespace App\Services\Sale;

use App\Models\Customer;
use App\Models\Provider;
use App\Models\Product;
use App\Models\FormPayment;
use App\Models\User;
use App\Notifications\ProviderNotication;

class SaleService
{

    public $details;

    public function setParams($details)
    {
        $this->details = $details;
        return $this;
    }

    public function notification()
    {
        $provider = Provider::where('id', $this->details->provider_id)->first();
        if ($provider) {
            $customerDetails = [];
            $customer = Customer::where('id', $this->details->customer_id)->first();
            $product = Product::where('id', $this->details->product_id)->first();            
            
            $customerDetails['nameCustomer']=$customer->name;
            $customerDetails['phoneCustomer']=$customer->phone;
            $customerDetails['addressCustomer']=$customer->address." ".$customer->number;
            $customerDetails['cityCustomer']=$customer->city;
            $customerDetails['nameProduct']=$product->name;
            $customerDetails['priceProduct']=$product->price;
            $customerDetails['quantityProduct']=$this->details->quantity;
            $customerDetails['descriptionProduct']=$product->description;
            $customerDetails['attachmentProduct']=$product->attachment;                       
            $customerDetails['total']=($this->details->quantity*$product->price);            
            $customerDetails['formPayment']=$this->details->form_payment==1?"Cartão":"Dinheiro";            
                        
            $userDetailsNotification = User::find($provider->user_id);                        
            
            if ($userDetailsNotification) {
                $userDetailsNotification->notify(new ProviderNotication($customerDetails));
            }
        }
    }

}
