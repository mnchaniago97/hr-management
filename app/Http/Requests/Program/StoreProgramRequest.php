<?php

namespace App\Http\Requests\Program;

use App\Models\Program\Program;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProgramRequest extends FormRequest
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
            'field_id' => 'required|exists:program_fields,id',
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(Program::getAvailableTypes())],
            'description' => 'nullable|string',
            'target' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'status' => ['nullable', 'string', Rule::in(Program::getAvailableStatuses())],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'field_id.required' => 'Bidang wajib dipilih',
            'field_id.exists' => 'Bidang tidak valid',
            'name.required' => 'Nama program wajib diisi',
            'name.max' => 'Nama program maksimal 255 karakter',
            'type.required' => 'Tipe program wajib dipilih',
            'type.in' => 'Tipe program tidak valid',
            'target.integer' => 'Target harus berupa angka',
            'target.min' => 'Target tidak boleh negatif',
            'budget.numeric' => 'Angggaran harus berupa angka',
            'budget.min' => 'Anggaran tidak boleh negatif',
            'status.in' => 'Status tidak valid',
            'start_date.date' => 'Tanggal mulai tidak valid',
            'end_date.date' => 'Tanggal selesai tidak valid',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'field_id' => 'Bidang',
            'name' => 'Nama Program',
            'type' => 'Tipe Program',
            'description' => 'Deskripsi',
            'target' => 'Target',
            'budget' => 'Anggaran',
            'status' => 'Status',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
        ];
    }
}
