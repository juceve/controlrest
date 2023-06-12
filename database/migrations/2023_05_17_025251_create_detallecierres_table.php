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
        Schema::create('detallecierres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cierrecaja_id')->constrained();
            $table->string('descripcion',100);
            $table->string('tipopago');
            $table->string('descuento');
            $table->integer('cantidad');
            $table->float('preciounitario');
            $table->float('importe');
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
        Schema::dropIfExists('detallecierres');
    }
};
