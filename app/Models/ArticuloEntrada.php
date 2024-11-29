<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloEntrada extends Model
{
    use HasFactory;

    protected $fillable = ['entrada_id', 'material_id', 'cantidad', 'unidad_medidas_id'];

    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function unidad_medidas()
    {
        return $this->belongsTo(UnidadMedidas::class, 'unidad_medidas_id');
    }
}
