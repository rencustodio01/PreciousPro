<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Production Manager']);
    }

    public function rules(): array
    {
        return [
            'product_id'        => ['required', 'exists:products,id'],
            'production_date'   => ['required', 'date', 'before_or_equal:today'],
            'quantity_produced' => ['required', 'integer', 'min:1'],
            'status'            => ['required', 'in:Pending,Completed'],
        ];
    }
}
