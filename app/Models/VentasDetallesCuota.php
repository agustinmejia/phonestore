<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VentasDetallesCuota extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ventas_detalle_id', 'tipo', 'monto', 'descuento', 'fecha', 'estado'
    ];

    function pagos(){
        return $this->hasMany(VentasDetallesCuotasPago::class)->withTrashed();
    }

    function detalle(){
        return $this->belongsTo(VentasDetalle::class, 'ventas_detalle_id')->withTrashed();
    }
}
