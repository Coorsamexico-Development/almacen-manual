<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entradas_real extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entrada_id',
        'cantidad',
        'disponible',
    ];

    public function productosTarimas()
    {
        return $this->hasMany(productos_tarimas::class, 'entradas_real_id');
    }
}
