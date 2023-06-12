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
        Schema::create('cierrecajas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('hora');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('sucursale_id')->constrained();
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
        Schema::dropIfExists('cierrecajas');
    }
};
