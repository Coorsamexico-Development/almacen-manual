<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductosExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect();
    }

    public function headings(): array //retornamos los encabezados
    {

        return [
            'EAN',
            'DESCRIPCION',
            'FAMILIA'
        ];
    }
}
