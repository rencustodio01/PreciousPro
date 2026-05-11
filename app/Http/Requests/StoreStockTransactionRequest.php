<?php

namespace App\Http\Requests;

use App\Models\Inventory;
use Illuminate\Contracts\Validation\Validator;
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
            'location_from'    => ['nullable', 'string', 'max:255'],
            'location_to'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->transaction_type === 'Stock Out' && $this->filled('quantity')) {
                $inventory = Inventory::find($this->inventory_id);

                if ($inventory && $this->quantity > $inventory->quantity_available) {
                    $validator->errors()->add(
                        'quantity',
                        '⚠️ Insufficient stock. Available: ' . $inventory->quantity_available
                    );
                }
            }
        });
    }
}
