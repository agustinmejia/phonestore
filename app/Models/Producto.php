<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'tipos_producto_id', 'compra_id', 'imei', 'precio_compra', 'precio_venta', 'precio_venta_contado', 'estado'
    ];

    function tipo(){
        return $this->belongsTo(TiposProducto::class, 'tipos_producto_id');
    }
}
