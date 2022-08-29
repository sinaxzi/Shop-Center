<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCustomerLoginRequest;
use App\Http\Requests\GetCustomerRegisterRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class CustomerAuthController extends Controller
{
    public function register(GetCustomerRegisterRequest $request): JsonResponse
    {
        $customer = Customer::create([
            'name' => $request->input('name'),
            'family' => $request->input('family'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $token = $customer->createToken('customerToken',$request->ip(),['shop:nearest','image:create'])->plainTextToken;

        $response = [
            'customer' => $customer,
            'token' => $token
        ];

        return $this->response(200,null,$response);
    }

    public function login(GetCustomerLoginRequest $request):JsonResponse
    {
        $customer = Customer::where('email',$request->input('email'))->first();

        if(!$customer || !Hash::check($request->input('password'),$customer->password)){
            return $this->response(401,Lang::get('messages/errors.bad_creds'));
        }

        $token = $customer->createToken('customerToken',$request->ip(),['shop:nearest','image:create'])->plainTextToken;

        $response = [
            'customer' => $customer,
            'token' => $token
        ];

        return $this->response(200,null,$response);
    }

    public function logout(Request $request):JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response(200,Lang::get('auth.logout'));
    }
}
