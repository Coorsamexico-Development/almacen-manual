<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'termino',
    ];


    public function nivels()
    {
        return $this->hasMany(nivel::class, 'rack_id');
    }
    public function columns()
    {
        return $this->hasMany(columna::class, 'rack_id');
    }
}
