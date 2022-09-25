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
}
