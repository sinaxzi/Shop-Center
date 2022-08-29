<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCreateImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|image|max:1024',
            'name' => 'required|string'
        ];
    }
}
