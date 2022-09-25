<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entrada extends Model
{
    use HasFactory;


    protected $fillable = [
        'producto_id',
        'folio_id',
        'cantidad',
    ];

    public function productosTarimas()
    {
        return $this->hasManyThrough(productos_tarimas::class, entradas_real::class);
    }

    public function entradasReales()
    {
        return $this->hasMany(entradas_real::class, 'entrada_id');
    }
}
