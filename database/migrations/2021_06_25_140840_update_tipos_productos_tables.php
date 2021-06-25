<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTiposProductosTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_productos', function(Blueprint $table)
		{
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->after('marca_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_productos', function (Blueprint $table) {
            $table->dropColumn(['categoria_id']);
        });
    }
}
