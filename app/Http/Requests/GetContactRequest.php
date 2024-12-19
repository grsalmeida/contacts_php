<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => 'nullable|string|max:255',
            'complement' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10'
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
