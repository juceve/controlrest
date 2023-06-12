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
        Schema::create('bonoanuales', function (Blueprint $table) {
            $table->id();
            $table->integer('gestion');
            $table->foreignId('estudiante_id')->constrained();
            $table->foreignId('tipomenu_id')->constrained();
            $table->foreignId('venta_id')->nullable()->constrained();
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
        Schema::dropIfExists('bonoanuales');
    }
};
