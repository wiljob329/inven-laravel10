<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['descripcion', 'cantidad', 'depositos_id', 'categorias_id', 'alerta'];

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'depositos_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categorias_id');
    }

    public function articuloEntradas()
    {
        return $this->hasMany(ArticuloEntrada::class);
    }

    public function articuloSalidas()
    {
        return $this->hasMany(ArticuloSalida::class);
    }
}
