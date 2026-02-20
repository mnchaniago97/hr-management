<?php

namespace App\Imports;

use App\Models\Asset\AssetItem;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AssetItemsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    use SkipsFailures;

    public function model(array $row): ?AssetItem
    {
        $purchaseDate = $this->parseDate($row['purchase_date'] ?? null);
        $purchasePrice = $row['purchase_price'] ?? null;

        return new AssetItem([
            'name' => trim((string) ($row['name'] ?? '')),
            'code' => trim((string) ($row['code'] ?? '')),
            'category' => trim((string) ($row['category'] ?? '')),
            'location' => trim((string) ($row['location'] ?? '')),
            'purchase_date' => $purchaseDate,
            'purchase_price' => $purchasePrice !== null && $purchasePrice !== '' ? (float) $purchasePrice : null,
            'condition' => strtolower(trim((string) ($row['condition'] ?? ''))),
            'status' => strtolower(trim((string) ($row['status'] ?? ''))),
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.code' => 'required|string|max:50|unique:asset_items,code',
            '*.category' => 'required|string|max:255',
            '*.location' => 'required|string|max:255',
            '*.purchase_date' => 'nullable|date',
            '*.purchase_price' => 'nullable|numeric|min:0',
            '*.condition' => ['required', Rule::in(['new', 'good', 'fair', 'poor'])],
            '*.status' => ['required', Rule::in(['available', 'borrowed', 'maintenance', 'retired'])],
            '*.description' => 'nullable|string',
        ];
    }

    public function prepareForValidation(array $data, int $index): array
    {
        $data['condition'] = strtolower(trim((string) ($data['condition'] ?? '')));
        $data['status'] = strtolower(trim((string) ($data['status'] ?? '')));
        $data['purchase_date'] = $this->parseDate($data['purchase_date'] ?? null);

        return $data;
    }

    public function customValidationMessages(): array
    {
        return [
            '*.name.required' => 'Nama wajib diisi.',
            '*.code.required' => 'Kode wajib diisi.',
            '*.code.unique' => 'Kode sudah digunakan.',
            '*.category.required' => 'Kategori wajib diisi.',
            '*.location.required' => 'Lokasi wajib diisi.',
            '*.condition.required' => 'Kondisi wajib diisi.',
            '*.condition.in' => 'Kondisi harus salah satu: new, good, fair, poor.',
            '*.status.required' => 'Status wajib diisi.',
            '*.status.in' => 'Status harus salah satu: available, borrowed, maintenance, retired.',
            '*.purchase_date.date' => 'Tanggal pembelian tidak valid.',
            '*.purchase_price.numeric' => 'Harga pembelian harus angka.',
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
