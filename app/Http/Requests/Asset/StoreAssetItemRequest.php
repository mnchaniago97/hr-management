<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:asset_items,code',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,good,fair,poor',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama aset wajib diisi.',
            'code.required' => 'Kode aset wajib diisi.',
            'code.unique' => 'Kode aset sudah terdaftar.',
            'category.required' => 'Kategori wajib diisi.',
            'location.required' => 'Lokasi wajib diisi.',
            'condition.required' => 'Kondisi wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ];
    }
}
