<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productos_tarimas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'productos_tarimas';

    protected $fillable = [
        'entradas_real_id',
        'tarima_id',
        'cant_disponible',
        'producto_id',
        'user_id',
    ];
}
