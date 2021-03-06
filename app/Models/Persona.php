<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nombre_completo', 'ci', 'telefono', 'direccion', 'trabajo', 'foto', 'archivos', 'observaciones'
    ];

    function ventas(){
        return $this->hasMany(Venta::class)->withTrashed();
    }

    function garante(){
        return $this->hasMany(VentasGarante::class)->withTrashed();
    }
}
