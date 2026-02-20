<?php

namespace App\Http\Requests\Program;

use App\Models\Program\Activity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
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
            'program_id' => 'required|exists:programs,id',
            'division_id' => 'required|exists:program_divisions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', 'string', Rule::in(Activity::getAvailableTypes())],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
            'program_id.required' => 'Program wajib dipilih',
            'program_id.exists' => 'Program tidak valid',
            'division_id.required' => 'Divisi wajib dipilih',
            'division_id.exists' => 'Divisi tidak valid',
            'name.required' => 'Nama kegiatan wajib diisi',
            'name.max' => 'Nama kegiatan maksimal 255 karakter',
            'type.required' => 'Tipe kegiatan wajib dipilih',
            'type.in' => 'Tipe kegiatan tidak valid',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.date' => 'Tanggal mulai tidak valid',
            'end_date.required' => 'Tanggal selesai wajib diisi',
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('status')) {
            $this->merge([
                'status' => Activity::STATUS_DRAFT,
            ]);
        }

        if (!$this->has('registered_count')) {
            $this->merge([
                'registered_count' => 0,
            ]);
        }
    }
}
