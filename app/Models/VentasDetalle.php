<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id', 'producto_id', 'precio', 'observaciones'
    ];

    function producto(){
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    function cuotas(){
        return $this->hasMany(VentasDetallesCuota::class);
    }

    function venta(){
        return $this->belongsTo(Venta::class, 'venta_id');
    }
}
