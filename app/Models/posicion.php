<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nivel_id',
        'columna_id',
        'status_posicion_id',
    ];
}
