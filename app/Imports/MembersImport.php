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

class MembersImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    use SkipsFailures;

    public function onRow(Row $row): void
    {
        $data = $row->toArray();

        $name = trim((string) ($data['name'] ?? ''));
        $nia = trim((string) ($data['nia'] ?? ''));

        if ($name === '' || $nia === '') {
            return;
        }

        $status = strtolower(trim((string) ($data['status'] ?? 'active'))) ?: 'active';
        $phone = $this->normalizePhone($data['phone'] ?? null);

        $memberType = $data['member_type'] ?? null;
        if ($memberType === null || trim((string) $memberType) === '') {
            $memberType = 'AM';
        }

        $memberData = [
            'name' => $name,
            'phone' => $phone,
            'position' => trim((string) ($data['position'] ?? '')),
            'department' => trim((string) ($data['department'] ?? '')),
            'status' => $status,
            'member_type' => $memberType,
        ];

        $employee = Employee::where('nia', $nia)->first();
        if ($employee) {
            $member = Member::find($employee->member_id);
            if ($member) {
                $member->update($memberData);
            } else {
                $member = Member::create($memberData);
                $employee->update(['member_id' => $member->id, 'status' => $status]);
            }
        } else {
            $member = Member::create($memberData);
            Employee::create([
                'member_id' => $member->id,
                'nia' => $nia,
                'status' => $status,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => 'nullable|string|max:255',
            '*.phone' => 'nullable|max:20',
            '*.position' => 'nullable|string|max:255',
            '*.department' => 'nullable|string|max:255',
            '*.status' => ['nullable', Rule::in(['active', 'inactive', 'on_leave', 'terminated'])],
            '*.member_type' => 'nullable|string|max:50',
            '*.nia' => 'nullable|string|max:50',
        ];
    }

    public function prepareForValidation(array $data, int $index): array
    {
        $data['status'] = strtolower(trim((string) ($data['status'] ?? 'active')));

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            '*.status.in' => 'Status harus salah satu: active, inactive, on_leave, terminated.',
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

    private function normalizePhone(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $phone = trim((string) $value);
        if ($phone === '') {
            return null;
        }

        return substr($phone, 0, 20);
    }
}
