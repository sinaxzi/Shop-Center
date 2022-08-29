<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetManagerLoginRequest;
use App\Http\Requests\GetManagerRegisterRequest;
use App\Models\Manager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class ManagerAuthController extends Controller
{
    public function register(GetManagerRegisterRequest $request): JsonResponse
    {
        $manager = Manager::create([
            'name' => $request->input('name'),
            'family' => $request->input('family'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $token = $manager->createToken('managerToken', $request->ip(), ['shop:nearest', 'shop:create', 'shop:update', 'image:create'])->plainTextToken;

        $response = [
            'manager' => $manager,
            'token' => $token
        ];

        return $this->response(200, null, $response);
    }

    public function login(GetManagerLoginRequest $request): JsonResponse
    {
        $manager = Manager::where('email', $request->input('email'))->first();

        if (!$manager || !Hash::check($request->input('password'), $manager->password)) {
            return $this->response(401, Lang::get('messages/errors.bad_creds'));
        }

        $token = $manager->createToken('managerToken', $request->ip(), ['shop:nearest', 'shop:create', 'shop:update', 'image:create'])->plainTextToken;

        $response = [
            'manager' => $manager,
            'token' => $token
        ];

        return $this->response(200, null, $response);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->response(200, Lang::get('auth.logout'));
    }
}
