<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oc extends Model
{
    use HasFactory;


    public $fillable = [
        'cliente_id',
        'name',
        'fecha_entrega',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'fecha_entrega' => 'datetime:d/m/Y'
    ];

    public function salidas()
    {
        return $this->hasMany(salida::class, 'oc_id');
    }


    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i');
    }
}
