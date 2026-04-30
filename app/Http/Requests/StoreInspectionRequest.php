<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['Admin', 'QC Officer']);
    }

    public function rules(): array
    {
        return [
            'production_id'   => ['required', 'exists:productions,id'],
            'inspection_date' => ['required', 'date', 'before_or_equal:today'],
            'result'          => ['required', 'in:Pass,Fail'],
            'remarks'         => ['nullable', 'string', 'max:150'],
        ];
    }
}
