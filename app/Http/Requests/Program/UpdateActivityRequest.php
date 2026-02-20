<?php

namespace App\Http\Requests\Program;

use App\Models\Program\Activity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
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
            'program_id' => 'sometimes|exists:programs,id',
            'division_id' => 'sometimes|exists:program_divisions,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => ['sometimes', 'string', Rule::in(Activity::getAvailableTypes())],
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'registered_count' => 'nullable|integer|min:0',
            'status' => ['nullable', 'string', Rule::in(Activity::getAvailableStatuses())],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'program_id.exists' => 'Program tidak valid',
            'division_id.exists' => 'Divisi tidak valid',
            'name.max' => 'Nama kegiatan maksimal 255 karakter',
            'type.in' => 'Tipe kegiatan tidak valid',
            'start_date.date' => 'Tanggal mulai tidak valid',
            'end_date.date' => 'Tanggal selesai tidak valid',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'location.max' => 'Lokasi maksimal 255 karakter',
            'capacity.integer' => 'Kapasitas harus berupa angka',
            'capacity.min' => 'Kapasitas tidak boleh negatif',
            'registered_count.integer' => 'Jumlah terdaftar harus berupa angka',
            'registered_count.min' => 'Jumlah terdaftar tidak boleh negatif',
            'status.in' => 'Status tidak valid',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'program_id' => 'Program',
            'division_id' => 'Divisi',
            'name' => 'Nama Kegiatan',
            'description' => 'Deskripsi',
            'type' => 'Tipe Kegiatan',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
            'location' => 'Lokasi',
            'capacity' => 'Kapasitas',
            'registered_count' => 'Jumlah Terdaftar',
            'status' => 'Status',
        ];
    }
}
