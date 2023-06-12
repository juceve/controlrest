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
        Schema::create('loncheras', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('estudiante_id')->constrained();
            $table->foreignId('venta_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('habilitado')->default(false);
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
        Schema::dropIfExists('loncheras');
    }
};
