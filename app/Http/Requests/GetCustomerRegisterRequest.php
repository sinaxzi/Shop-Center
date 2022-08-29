<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCustomerRegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'family' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required'
        ];
    }
}
