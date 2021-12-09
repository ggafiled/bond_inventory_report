<?php

namespace App\Services\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LegalTasksExport implements FromCollection, WithHeadings, WithMapping
{

    use Exportable;

    protected $spreadsheet;

    public function __construct($spreadsheet)
    {
        $this->spreadsheet = $spreadsheet;
    }

    public function collection()
    {
        return $this->spreadsheet;
    }

    public function map($item): array
    {
        return [
           "",
           $item["HAWB"],
           $item["Location"],
           $item["Weight"],
           $item["Pcs"],
           $item["Scanned"],
           $item["Status"]
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'HAWB',
            'Location',
            'Weight',
            'Pcs',
            'Scanned',
            'Status'
        ];
    }
}