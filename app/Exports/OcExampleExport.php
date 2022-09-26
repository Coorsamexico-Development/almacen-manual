<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OcExampleExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect();
    }

    public function headings(): array
    {

        return [
            'CLIENTE',
            'OC',
            'FECHA ENTREGA',
            'EAN',
            'CANTIDAD'
        ];
    }
}
