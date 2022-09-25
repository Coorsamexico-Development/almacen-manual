<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tarima extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];



    public function posiciones()
    {
        $this->belongsToMany(posicion::class, 'tarima_posicions', 'tarima_id', 'posicion_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i');
    }
}
