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
}
