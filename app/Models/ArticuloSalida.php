<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloSalida extends Model
{
    use HasFactory;

    protected $fillable = ['salida_id', 'material_id', 'cantidad'];

    public function salida()
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
