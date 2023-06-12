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
        Schema::create('entregalounches', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechaentrega');
            $table->foreignId('detallelonchera_id')->nullable()->constrained();
            $table->foreignId('menu_id')->nullable()->constrained();
            $table->foreignId('venta_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('sucursale_id')->constrained();
            $table->foreignId('estudiante_id')->nullable()->constrained();
            $table->boolean('estado')->default(true);
            $table->string('observaciones')->nullable();
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
        Schema::dropIfExists('entregalounches');
    }
};
