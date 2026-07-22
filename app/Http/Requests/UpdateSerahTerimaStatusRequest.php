<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSerahTerimaStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'pengelola';
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:preparing_delivery,in_delivery,delivered'],
            'delivery_method' => ['nullable', 'string', 'max:255'],
            'delivery_location' => ['nullable', 'string', 'max:1000'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
