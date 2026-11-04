<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole([
            'admin',
            'superadmin',
        ]) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                'unique:ingredients,name',
            ],

            'unit' => [
                'required',
                'string',
                'max:20',
                Rule::in([
                    'gram',
                    'kg',
                    'ml',
                    'liter',
                    'pcs',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama bahan baku wajib diisi.',
            'name.unique' => 'Nama bahan baku tersebut sudah terdaftar.',
            'unit.required' => 'Satuan bahan baku wajib dipilih.',
            'unit.in' => 'Satuan yang dipilih tidak valid.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}
