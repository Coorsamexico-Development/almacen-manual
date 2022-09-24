<?php

namespace App\Imports;

use App\Models\folio;
use App\Models\ordenes_entrada;
use App\Models\producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrdenEntradaImport implements ToCollection, WithHeadingRow
{

    protected $userId;
    public $errors = [];
    public $ordenEntrada;

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
                $fechaArmado = Date::excelToDateTimeObject($row['fecha_armado'])->format('Y-m-d');
                $this->ordenEntrada = ordenes_entrada::updateOrCreate([
                    'name' => $row['folio_orden_traslado'] . '-' . $row['origen'],
                ], [
                    'user_id' => $this->userId,
                    'origen' =>  $row['origen'],
                    'fecha_armado' => $fechaArmado,
                ]);
                $isFirst = false;
            }
            if (!empty($row['folio_orden_traslado'])) {
                $folio = folio::firstOrCreate([
                    'ordenes_entrada_id' => $this->ordenEntrada->id,
                    'name' => $row['folio_orden_traslado']
                ]);
            }
            if (isset($folio)) {
                $ean = producto::select('id', 'name')->firstWhere(
                    'ean',
                    '=',
                    trim($row['ean'])
                );
                if (!empty($ean)) {
                    $this->ordenEntrada->entradas()->updateOrCreate([
                        'producto_id' => $ean->id,
                        'folio_id' => $folio->id
                    ], ['cantidad' => $row['cantidad']]);
                } else {
                    $this->errors[] = 'El ean ' . $row['ean'] . ' no se encuentra registrado';
                }
            } else {
                $this->errors[] = 'Folio  no definido';
                break;
            }
        }
    }
}
