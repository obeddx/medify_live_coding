<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    private int $no = 0;

    public function collection()
    {
        return MasterItem::with('kategoris')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Kategori', 'Nama Items', 'Nama Supplier', 'Harga', 'Laba', 'Harga Jual'];
    }

    public function map($item): array
    {
        return [
            ++$this->no,
            $item->kategoris->pluck('nama')->implode(', '),  // dipisah koma
            $item->nama,
            $item->supplier,
            $item->harga_beli,
            $item->laba / 100,      // tampil sebagai persen di Excel (mis. 25%)
            $item->harga_jual,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0',
            'F' => NumberFormat::FORMAT_PERCENTAGE,
            'G' => '#,##0',
        ];
    }
}