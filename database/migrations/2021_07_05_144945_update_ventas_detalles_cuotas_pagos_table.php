<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVentasDetallesCuotasPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas_detalles_cuotas_pagos', function(Blueprint $table)
		{
            $table->integer('efectivo')->nullable()->default(1)->after('monto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_detalles_cuotas_pagos', function (Blueprint $table) {
            $table->dropColumn(['efectivo']);
        });
    }
}
