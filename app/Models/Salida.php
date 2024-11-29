<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;

    protected $fillable = ['fecha',  'destino', 'solicitantes_id', 'vehiculos_id', 'caso', 'serial', 'codigo_uxd'];

    public function articulos()
    {
        return $this->hasMany(ArticuloSalida::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'solicitantes_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculos_id');
    }

    public static function getNextCode(): string
    {
        $lastSalida = static::orderBy('id', 'desc')->first();

        if (! $lastSalida) {
            $number = 1;
        } else {
            $number = (int) str_replace('SA', '', $lastSalida->id) + 1;
        }

        return 'SA'.str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
