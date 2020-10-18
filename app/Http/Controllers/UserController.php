<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use App\Models\Customer;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Repositories\VerifyCnpj;
use function bcrypt;
use App\Filters\User\UserFilters;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = (new UserFilters())->apply($request->all());
        return response()->json(['data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatorUser = Validator::make($request->all(), User::rules());

        if ($validatorUser->fails()) {
            return response()->json(['error' => $validatorUser->errors()]);
        }


        DB::beginTransaction();
        try {

            $user = User::create([
                        "email" => $request->email,
                        "password" => bcrypt($request->password),
                        "active" => 1,
                        "is_provider" => $request->is_provider,
                        "is_admin" => $request->is_admin,
                        "last_name" => $request->last_name,
                        "name" => $request->name,
            ]);

            if ($user && ($request->is_provider == 1)) {
                $validatorProvider = Validator::make($request->all(), Provider::rules());
                if ($validatorProvider->fails()) {
                    return response()->json(['error' => $validatorProvider->errors()]);
                }
                if (!VerifyCnpj::validar_cnpj($request->cnpj)) {
                    return response()->json(['error' => 'cnpj invalid']);
                }
                Provider::create([
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'number' => $request->number,
                    'city' => $request->city,
                    'cnpj' => $request->cnpj,
                    'user_id' => $user->id
                ]);
            } else {
                $validatorCustomer = Validator::make($request->all(), Customer::rules());
                if ($validatorCustomer->fails()) {
                    return response()->json(['error' => $validatorCustomer->errors()]);
                }
                Customer::create([
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'number' => $request->number,
                    'city' => $request->city,
                    'user_id' => $user->id
                ]);
            }
            DB::commit();
            return response()->json(['data' => $user->is_provider == 1 ?
                        $user->with('providers')->where('id', $user->id)->first() :
                        $user->with('customer')->where('id', $user->id)->first()
                            ], 200);
        } catch (Exception $ex) {
            return response()->json(['data' => $ex->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = new User();

        if (auth()->user()->is_provider == 1) {
            $user = $users->with('providers')->where('id', $id)->first();
            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['data' => 'user not found']);
            }
        } else {
            $user = $users->with('customer')->where('id', $id)->first();
            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['data' => 'user not found']);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatorUser = Validator::make($request->all(), User::rules($id));

        if ($validatorUser->fails()) {
            return response()->json(['error' => $validatorUser->errors()]);
        }

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->update([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "is_provider" => $request->is_provider,
            ]);
            if ($user && ($request->is_provider == 1)) {
                $validatorProvider = Validator::make($request->all(), Provider::rules($id));
                if ($validatorProvider->fails()) {
                    return response()->json(['error' => $validatorProvider->errors()]);
                }
                if (!VerifyCnpj::validar_cnpj($request->cnpj)) {
                    return response()->json(['error' => 'cnpj invalid']);
                }
                $provider = Provider::where('user_id', $user->id)->first();
                if ($provider) {
                    $provider->update([
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'number' => $request->number,
                        'city' => $request->city,
                        'cnpj' => $request->cnpj,
                    ]);
                } else {
                    return response()->json(['error' => 'user not found']);
                }
            } else {
                $validatorCustomer = Validator::make($request->all(), Customer::rules());
                if ($validatorCustomer->fails()) {
                    return response()->json(['error' => $validatorCustomer->errors()]);
                }
                $customer = Customer::where('user_id', $user->id)->first();
                if ($customer) {
                    $customer->update([
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'number' => $request->number,
                        'city' => $request->city,
                    ]);
                } else {
                    return response()->json(['data' => 'user not found']);
                }
            }
            DB::commit();
            return response()->json(['data' => $user->is_provider == 1 ?
                        $user->with('providers')->where('id', $user->id)->first() :
                        $user->with('customer')->where('id', $user->id)->first()
                            ], 200);
        } catch (Exception $ex) {
            return response()->json(['data' => $ex->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = null;

        if ($request->disable) {

            User::where("id", $id)->update([
                "active" => 0
            ]);
            $data = "disabled user successfully";
        } else {
            User::where("id", $id)->update([
                "active" => 1
            ]);
            $data = "enabled user successfully";
        }
        return response()->json(['data' => $data], 200);
    }

}
