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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',10)->unique()->nullable();
            $table->string('nombre');
            $table->string('cedula')->nullable();
            $table->string('correo',100)->nullable();
            $table->string('telefono')->nullable();
            $table->foreignId('tutore_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('curso_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('verificado')->default(true);
            $table->boolean('esestudiante')->default(true);
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
        Schema::dropIfExists('estudiantes');
    }
};
