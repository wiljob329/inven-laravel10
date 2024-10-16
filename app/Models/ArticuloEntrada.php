<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloEntrada extends Model
{
    use HasFactory;

    protected $fillable = ['entrada_id', 'material_id', 'cantidad'];

    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
