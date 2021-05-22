<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentasDetallesCuotasPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'ventas_detalles_cuota_id', 'user_id', 'monto', 'observaciones'
    ];
}
