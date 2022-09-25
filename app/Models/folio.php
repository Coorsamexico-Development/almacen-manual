<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class folio extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordenes_entrada_id',
        'name'
    ];

    public function entradas()
    {
        return $this->hasMany(entrada::class, 'folio_id');
    }
}
