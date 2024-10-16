<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = ['codigo_nota_entrega', 'fecha', 'recibido_por', 'proveedors_id'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedors_id');
    }

    public function articulos()
    {
        return $this->hasMany(ArticuloEntrada::class);
    }
}
