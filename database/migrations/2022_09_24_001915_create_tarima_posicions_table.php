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
        Schema::create('tarima_posicions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posicion_id')->constrained('posicions');
            $table->foreignId('tarima_id')->constrained('tarimas');
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
        Schema::dropIfExists('tarima_posicions');
    }
};
