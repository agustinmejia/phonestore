<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'persona_id', 'user_id', 'fecha', 'observaciones', 'descuento', 'iva'
    ];

    function detalles(){
        return $this->hasMany(VentasDetalle::class)->withTrashed();
    }

    function garantes(){
        return $this->hasMany(VentasGarante::class)->withTrashed();
    }

    function cliente(){
        return $this->belongsTo(Persona::class, 'persona_id')->withTrashed();
    }

    function empleado(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
