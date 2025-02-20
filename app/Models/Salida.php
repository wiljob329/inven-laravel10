<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Salida extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'fecha',
        'destino',
        'solicitantes_id',
        'vehiculos_id',
        'caso',
        'serial',
        'codigo_uxd',
        'encargado_id',
        'jefe_id',
        'cuadrilla_id',
        'es_cuadrilla',
        'municipio_id'
    ];

    protected $casts = [
        'es_cuadrilla' => 'boolean',
    ];

    public function articulos()
    {
        return $this->hasMany(ArticuloSalida::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'solicitantes_id');
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado_id');
    }

    public function jefe()
    {
        return $this->belongsTo(Jefe::class, 'jefe_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculos_id');
    }

    public function cuadrilla()
    {
        return $this->belongsTo(Cuadrilla::class, 'cuadrilla_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public static function getNextCode(): string
    {
        
        $lastSalida = DB::table('salidas')->count();
        $before = 'SA-'.date('Y');
        if (! $lastSalida) {
            $number = 1;
        } else {
            // $number = (int) str_replace('SA', '', $lastSalida->id) + 1;
            $number = $lastSalida + 1;
        }

        return $before.str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
