<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExampleEntradaExport implements FromCollection, WithHeadings
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
            'ORIGEN',
            'FECHA ARMADO',
            'FOLIO_ORDEN_TRASLADO',
            'EAN',
            'CANTIDAD'
        ];
    }
}
