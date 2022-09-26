<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salida extends Model
{
    use HasFactory;


    protected $fillable = [
        'oc_id',
        'producto_id',
        'solicitado',
        'surtido'
    ];
}
