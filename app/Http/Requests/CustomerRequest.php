<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_code' => ['required', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
        ];
    }
}
