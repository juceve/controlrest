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
        Schema::create('detalleeventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained();
            $table->foreignId('menu_id')->constrained();
            $table->integer('stock')->nullable();
            $table->string('tipo',50)->nullable();
            $table->timestamps();
        });
    }
 
    public function down()
    {
        Schema::dropIfExists('detalleeventos');
    }
};
