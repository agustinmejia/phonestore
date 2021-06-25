<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'proveedor_id', 'user_id', 'observaciones'
    ];

    function producto(){
        return $this->hasMany(Producto::class)->withTrashed();
    }

    function proveedor(){
        return $this->belongsTo(Proveedore::class, 'proveedor_id')->withTrashed();
    }

    function empleado(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
