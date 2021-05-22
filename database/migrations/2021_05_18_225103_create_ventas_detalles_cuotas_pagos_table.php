<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasDetallesCuotasPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_detalles_cuotas_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ventas_detalles_cuota_id')->nullable()->constrained('ventas_detalles_cuotas');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->decimal('monto', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_detalles_cuotas_pagos');
    }
}
