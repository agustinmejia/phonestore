<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id', 'user_id', 'fecha', 'observaciones'
    ];

    function detalles(){
        return $this->hasMany(VentasDetalle::class);
    }

    function garantes(){
        return $this->hasMany(VentasGarante::class);
    }

    function cliente(){
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    function empleado(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
