<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasDetallesCuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'ventas_detalle_id', 'tipo', 'monto', 'fecha', 'estado'
    ];

    function pagos(){
        return $this->hasMany(VentasDetallesCuotasPago::class);
    }
}
