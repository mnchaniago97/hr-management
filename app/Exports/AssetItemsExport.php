<?php

namespace App\Exports;

use App\Models\Asset\AssetItem;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Builder $query)
    {
    }

    public function collection()
    {
        return $this->query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'category',
            'location',
            'purchase_date',
            'purchase_price',
            'condition',
            'status',
            'description',
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->code,
            $item->category,
            $item->location,
            optional($item->purchase_date)->format('Y-m-d'),
            $item->purchase_price,
            $item->condition,
            $item->status,
            $item->description,
        ];
    }
}
