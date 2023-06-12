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
        Schema::create('bonofechas', function (Blueprint $table) {
            $table->id();
            $table->date('fechainicio');
            $table->date('fechafin');
            $table->foreignId('estudiante_id')->constrained();
            $table->foreignId('tipomenu_id')->constrained();
            $table->foreignId('venta_id')->constrained();
            $table->boolean('estado');
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
        Schema::dropIfExists('bonofechas');
    }
};
