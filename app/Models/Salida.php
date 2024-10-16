<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'entregado_a', 'departmento', 'cedula', 'vehicle_placa'];

    public function articulos()
    {
        return $this->hasMany(ArticuloSalida::class);
    }
}
