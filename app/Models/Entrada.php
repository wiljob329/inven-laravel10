<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Entrada extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = ['codigo_nota_entrega', 'fecha', 'encargado_id', 'proveedors_id', 'cuadrilla_id'];

    public static function getNextCode(): string
    {
        $lastEntrada = static::orderBy('id', 'desc')->first();

        if (! $lastEntrada) {
            $number = 1;
        } else {
            $number = (int) str_replace('INV', '', $lastEntrada->codigo_nota_entrega) + 1;
        }

        return 'INV'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedors_id');
    }

    public function articulos()
    {
        return $this->hasMany(ArticuloEntrada::class);
    }

    public function cuadrilla()
    {
        return $this->belongsTo(Cuadrilla::class, 'cuadrilla_id');
    }
}
