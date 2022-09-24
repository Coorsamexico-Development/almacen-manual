<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordenes_entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'origen',
        'fecha_armado'
    ];


    public function entradas()
    {
        return $this->hasManyThrough(entrada::class, folio::class);
    }
}
