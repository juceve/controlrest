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
        Schema::create('preciomenus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nivelcurso_id')->constrained();
            $table->foreignId('tipomenu_id')->constrained();
            $table->decimal('precio',10,2);
            $table->decimal('preciopm',10,2)->nullable();
            $table->integer('cantmin')->nullable();
            $table->foreignId('sucursale_id')->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('preciomenus');
    }
};
