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


    public function folios()
    {
        return $this->hasMany(folio::class, 'ordenes_entrada_id');
    }
}
