<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'info_contacto'];

    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }
}
