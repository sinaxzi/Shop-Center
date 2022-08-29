<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetManagerRegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:managers,email',
            'password' => 'required'
        ];
    }
}
