<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Production Manager']);
    }

    public function rules(): array
    {
        return [
            'category_id'  => ['required', 'exists:categories,id'],
            'product_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:150'],
            'base_price'   => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'status'       => ['required', 'in:Active,In Production,Discontinued'],
        ];
    }
}
