<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'Finance Officer']);
    }

    public function rules(): array
    {
        return [
            'production_id' => ['required', 'exists:productions,id'],
            'cost_type'     => ['required', 'in:Material,Labor,Overhead'],
            'amount'        => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'record_date'   => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
