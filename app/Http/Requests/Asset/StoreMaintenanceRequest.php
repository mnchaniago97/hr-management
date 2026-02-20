<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
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
            'type' => 'required|in:preventive,corrective,inspection,upgrade',
            'maintenance_date' => 'required|date',
            'scheduled_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'performed_by' => 'nullable|string|max:255',
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
            'type.required' => 'Jenis perawatan wajib diisi.',
            'maintenance_date.required' => 'Tanggal perawatan wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
        ];
    }
}
