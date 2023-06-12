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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('cliente');            
            $table->foreignId('estadopago_id')->constrained()->default(1);
            $table->foreignId('tipopago_id')->constrained();
            $table->decimal('importe',10,2);
            $table->foreignId('sucursale_id')->nullable()->constrained();
            $table->string('plataforma',20)->nullable();
            $table->string('observaciones')->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('ventas');
    }
};
