<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'superadmin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'ingredient_id' => [
                'required',
                'integer',
                'exists:ingredients,id',
            ],
            'quantity' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,3',
                'max:99999999999',
            ],
            'batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],
            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}