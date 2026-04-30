<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Inventory Officer']);
    }

    public function rules(): array
    {
        return [
            'inventory_id'     => ['required', 'exists:inventories,id'],
            'transaction_type' => ['required', 'in:Stock In,Stock Out'],
            'quantity'         => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
        ];
    }
}
