<?php

namespace App\Imports;

use App\Models\Hr\Employee;
use App\Models\Hr\Member;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MembersImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    use SkipsFailures;

    public function onRow(Row $row): void
    {
        $data = $row->toArray();

        $joinDate = $this->parseDate($data['join_date'] ?? null);
        $status = strtolower(trim((string) ($data['status'] ?? 'active'))) ?: 'active';

        $member = Member::create([
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'phone' => $data['phone'] ?? null,
            'position' => trim((string) ($data['position'] ?? '')),
            'department' => trim((string) ($data['department'] ?? '')),
            'join_date' => $joinDate,
            'status' => $status,
            'member_type' => $data['member_type'] ?? null,
        ]);

        $nia = $data['nia'] ?? null;
        if ($nia !== null && $nia !== '') {
            Employee::updateOrCreate(
                ['member_id' => $member->id],
                ['nia' => trim((string) $nia), 'status' => $status]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.email' => 'required|email|unique:hr_members,email',
            '*.phone' => 'nullable|string|max:20',
            '*.position' => 'required|string|max:255',
            '*.department' => 'required|string|max:255',
            '*.join_date' => 'required|date',
            '*.status' => ['nullable', Rule::in(['active', 'inactive', 'on_leave', 'terminated'])],
            '*.member_type' => 'nullable|string|max:50',
            '*.nia' => 'nullable|string|max:50|unique:hr_employees,nia',
        ];
    }

    public function prepareForValidation(array $data, int $index): array
    {
        $data['status'] = strtolower(trim((string) ($data['status'] ?? 'active')));
        $data['join_date'] = $this->parseDate($data['join_date'] ?? null);

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'Nama wajib diisi.',
            '*.email.required' => 'Email wajib diisi.',
            '*.email.unique' => 'Email sudah digunakan.',
            '*.position.required' => 'Posisi wajib diisi.',
            '*.department.required' => 'Departemen wajib diisi.',
            '*.join_date.required' => 'Tanggal gabung wajib diisi.',
            '*.join_date.date' => 'Tanggal gabung tidak valid.',
            '*.status.in' => 'Status harus salah satu: active, inactive, on_leave, terminated.',
            '*.nia.unique' => 'NIA sudah digunakan.',
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return date('Y-m-d', strtotime((string) $value));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
