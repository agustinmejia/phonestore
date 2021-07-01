<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiposProducto extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'marca_id', 'categoria_id', 'nombre', 'slug', 'imagenes', 'detalles'
    ];

    function marca(){
        return $this->belongsTo(Marca::class, 'marca_id')->withTrashed();
    }

    function productos(){
        return $this->hasMany(Producto::class)->withTrashed();
    }
}
