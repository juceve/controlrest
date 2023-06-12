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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razonsocial');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('nit');
            $table->string('avatar')->nullable();
            $table->string('responsable');
            $table->string('telefono_responsable');
            $table->boolean('estado')->default(true);            
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
        Schema::dropIfExists('empresas');
    }
};
