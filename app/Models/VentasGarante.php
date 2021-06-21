<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasGarante extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id', 'persona_id', 'descripcion'
    ];

    function persona(){
        return $this->belongsTo(Persona::class, 'persona_id')->withTrashed();
    }
}
