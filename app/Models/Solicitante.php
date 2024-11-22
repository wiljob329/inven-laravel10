<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'cargo', 'gerencia', 'cedula'];

    public function salidas()
    {
        return $this->hasMany(Salida::class);
    }
}
