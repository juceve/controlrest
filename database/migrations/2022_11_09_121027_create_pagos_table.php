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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('recibo');                        
            $table->integer('tipopago_id');
            $table->string('tipopago');
            $table->integer('sucursal_id')->nullable();
            $table->string('sucursal')->nullable();
            $table->decimal('importe',10,2);
            $table->foreignId('venta_id')->constrained();              
            $table->foreignId('estadopago_id')->constrained();  
            $table->string('comprobante')->nullable();
            $table->string('tipoinicial',50)->nullable();
            $table->foreignId('estudiante_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained();   
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
        Schema::dropIfExists('pagos');
    }
};
