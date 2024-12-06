<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jefe extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'cargo', 'cedula'];

    public function salidas()
    {
        return $this->hasMany(Salida::class);
    }
}
