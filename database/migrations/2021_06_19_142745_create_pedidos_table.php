<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->nullable()->constrained('personas');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('equipo')->nullable();
            $table->decimal('precio_venta', 10, 2)->nullable()->default(0);
            $table->decimal('monto_entregado', 10, 2)->nullable()->default(0);
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('pedidos');
    }
}
