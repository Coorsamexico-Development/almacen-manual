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
        Schema::create('productos_tarima_salidas_real', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_id')->constrained('salidas');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('productos_tarima_id')->constrained('productos_tarimas');
            $table->integer('cantidad');
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
        Schema::dropIfExists('productos_tarima_salidas_real');
    }
};
