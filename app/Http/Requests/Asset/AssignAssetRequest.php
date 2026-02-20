<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AssignAssetRequest extends FormRequest
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
            'item_id' => 'required|exists:asset_items,id',
            'member_id' => 'required|exists:hr_members,id',
            'assignment_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:assignment_date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'item_id.required' => 'Aset wajib dipilih.',
            'member_id.required' => 'Anggota wajib dipilih.',
            'assignment_date.required' => 'Tanggal penugasan wajib diisi.',
            'return_date.required' => 'Tanggal kembali wajib diisi.',
            'return_date.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal penugasan.',
        ];
    }
}
