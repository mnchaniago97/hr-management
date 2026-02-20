<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class AssignDivisionRequest extends FormRequest
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
            'member_id' => 'required|exists:hr_members,id',
            'position_id' => 'required|exists:hr_positions,id',
            'division_id' => 'required|exists:program_divisions,id',
            'assigned_date' => 'required|date',
            'end_date' => 'nullable|date|after:assigned_date',
            'status' => 'required|in:active,completed,transferred',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'member_id.required' => 'Anggota wajib dipilih.',
            'position_id.required' => 'Jabatan wajib dipilih.',
            'division_id.required' => 'Divisi/Bidang wajib dipilih.',
            'assigned_date.required' => 'Tanggal penugasan wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ];
    }
}
