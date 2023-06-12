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
        Schema::create('detallemontocierreresbonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cierrereservabono_id')->constrained();
            $table->foreignId('tipopago_id')->constrained();
            $table->string('tipopago');
            $table->integer('cantidad');
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
        Schema::dropIfExists('detallemontocierreresbonos');
    }
};
