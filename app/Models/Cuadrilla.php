<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuadrilla extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }

    public function salidas()
    {
        return $this->hasMany(Salida::class);
    }
}
