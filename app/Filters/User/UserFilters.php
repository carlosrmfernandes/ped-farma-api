<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserFilters
 *
 * @author carlosfernandes
 */

namespace App\Filters\User;

use App\Models\User;

class UserFilters
{

    private $query;
    private $searchQuery;

    public function apply($request)
    {

        $this->query = User::query();
        if (!empty($request['searchQuery'])) {
            $this->query = User::where('name', 'ilike', '%' . $request['searchQuery'] . '%')->
                    orWhere('last_name', 'ilike', '%' . $request['searchQuery'] . '%');
        }

        return $this->query->get();
    }

}
