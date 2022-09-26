<?php

namespace App\Imports;

use App\Models\Cliente;
use App\Models\oc;
use App\Models\producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OcImport implements ToCollection, WithHeadingRow
{

    protected $userId;
    protected $documento;
    protected $folio;
    public $errors = [];
    public $oc;

    public function __construct(Int $userId)
    {
        $this->userId = $userId;
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $rows = $collection->filter(function ($row) {
            return !empty($row['ean']);
        });
        $isFirst = true;
        foreach ($rows as $row) {
            if ($isFirst) {
                $cliente = Cliente::firstOrCreate(['name' =>  $row['cliente']]);
                $fechaEntrega = Date::excelToDateTimeObject($row['fecha_entrega'])->format('Y-m-d');
                $this->oc = oc::updateOrCreate([
                    'cliente_id' => $cliente->id,
                    'name' => $row['oc']
                ], [
                    'user_id' => $this->userId,
                    'fecha_entrega' => $fechaEntrega,
                ]);


                $isFirst = false;
            }
            $ean = producto::select('id', 'ean')->firstWhere(
                'ean',
                '=',
                $row['ean']
            );
            if (!empty($ean)) {
                $this->oc->salidas()->updateOrCreate([
                    'producto_id' => $ean->id
                ], [
                    'solicitado' => $row['cantidad'],
                ]);
            } else {
                $this->errors[] = 'El ean: ' . $row['ean'] . ' no se encuentra registrado';
            }
        }
    }
}
