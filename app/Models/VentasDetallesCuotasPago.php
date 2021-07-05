<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VentasDetallesCuotasPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ventas_detalles_cuota_id', 'user_id', 'monto', 'efectivo', 'observaciones'
    ];

    function cuota(){
        return $this->belongsTo(VentasDetallesCuota::class, 'ventas_detalles_cuota_id')->withTrashed();
    }
}
