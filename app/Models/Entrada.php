<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Illuminate\Support\Facades\DB;

class Entrada extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = ['codigo_nota_entrega', 'fecha', 'encargado_id', 'proveedors_id', 'cuadrilla_id', 'es_cuadrilla'];

    protected $casts = [
        'es_cuadrilla' => 'boolean',
    ];

    public static function getNextCode(): string
    {
        $lastEntrada = DB::table('entradas')->count();
        $before = 'EN-'.date('Y');
        if (! $lastEntrada) {
            $number = 1;
        } else {
            // $number = (int) str_replace('SA', '', $lastSalida->id) + 1;
            $number = $lastEntrada + 1;
        }

        return $before.str_pad($number, 5, '0', STR_PAD_LEFT);
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
