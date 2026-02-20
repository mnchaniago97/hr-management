<?php

namespace App\Http\Requests\Program;

use App\Models\Program\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadDocumentRequest extends FormRequest
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
            'activity_id' => 'nullable|exists:program_activities,id',
            'program_id' => 'nullable|exists:programs,id',
            'type' => ['required', 'string', Rule::in(Document::getAvailableTypes())],
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,webp,zip,rar',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'activity_id.exists' => 'ID Kegiatan tidak valid',
            'program_id.exists' => 'ID Program tidak valid',
            'type.required' => 'Tipe dokumen wajib dipilih',
            'type.in' => 'Tipe dokumen tidak valid',
            'file.required' => 'File wajib diupload',
            'file.file' => 'File tidak valid',
            'file.max' => 'Ukuran file maksimal 10MB',
            'file.mimes' => 'Format file tidak didukung',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'activity_id' => 'ID Kegiatan',
            'program_id' => 'ID Program',
            'type' => 'Tipe Dokumen',
            'file' => 'File',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->has('activity_id') && !$this->has('program_id')) {
                $validator->errors()->add(
                    'activity_id',
                    'Minimal salah satu dari ID Kegiatan atau ID Program wajib diisi'
                );
                $validator->errors()->add(
                    'program_id',
                    'Minimal salah satu dari ID Kegiatan atau ID Program wajib diisi'
                );
            }
        });
    }
}
