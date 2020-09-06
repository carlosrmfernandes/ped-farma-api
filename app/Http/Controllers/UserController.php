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

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                    'name' => $request->name,
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
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'number' => $request->number,
                    'city' => $request->city,
                    'user_id' => $user->id
                ]);
            }
            DB::commit();
            return response()->json(['data' => $user], 200);
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
                return response()->json(['error' => 'user not found']);
            }
        } else {
            $user = $users->with('customer')->where('id', $id)->first();
            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['error' => 'user not found']);
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
                        'name' => $request->name,
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
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'number' => $request->number,
                        'city' => $request->city,
                    ]);
                } else {
                    return response()->json(['error' => 'user not found']);
                }
            }
            DB::commit();
            return response()->json(['data' => $user], 200);
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
            $user = User::find($id);
            if ($user->is_provider == 1) {
                Provider::where('user_id', $id)->delete();
            } else {
                Customer::where('user_id', $id)->delete();
            }
            User::destroy($id);
            $data = "user removed successfully";
        }
        return response()->json(['data' => $data], 200);
    }

}
