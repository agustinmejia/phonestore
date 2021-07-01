<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VentasGarante extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venta_id', 'persona_id', 'descripcion'
    ];

    function persona(){
        return $this->belongsTo(Persona::class, 'persona_id')->withTrashed();
    }

    function venta(){
        return $this->belongsTo(Venta::class, 'venta_id')->withTrashed();
    }
}
