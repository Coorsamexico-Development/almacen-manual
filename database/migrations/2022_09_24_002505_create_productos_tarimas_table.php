<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos_tarimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entradas_real_id')->constrained('entradas_reals');
            $table->foreignId('tarima_id')->constrained('tarimas');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('user_id')->constrained('users');
            $table->smallInteger('cant_disponible')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_tarimas');
    }
};
