<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedidas extends Model
{
    use HasFactory;

    protected $fillable = ['unidad'];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
