<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasDetallesCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_detalles_cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ventas_detalle_id')->nullable()->constrained('ventas_detalles');
            $table->string('tipo')->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->decimal('descuento', 10, 2)->nullable()->default(0);
            $table->date('fecha')->nullable();
            $table->string('estado')->nullable();
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
        Schema::dropIfExists('ventas_detalles_cuotas');
    }
}
