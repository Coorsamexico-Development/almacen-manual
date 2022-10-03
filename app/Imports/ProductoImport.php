<?php

namespace App\Imports;

use App\Models\familia;
use App\Models\producto;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductoImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $id;
    protected $ean;
    protected $descripcion;

    public function __construct(Int $id)
    {
        $this->id = $id;
    }

    public function collection(Collection $collection)
    {
        $rows = $collection->filter(function ($row) {
            return !empty($row['ean']);
        });
        $isFirst = true;
        foreach ($rows as $row) //recorremos todas las columnas del excel
        {
            if ($isFirst) 
            {
                $familia = familia::updateOrCreate(['name' =>  $row['familia']]); //creamos y ponemos la familia
                $this->producto = producto::updateOrCreate([ //actualizamos o creamos el producto con los sig campos
                    'name' => $row['descripcion'],
                    'familia_id' => $familia->id
                ], [
                    'ean' => $row['ean'],
                ]);

            }    
        }
    }
}
