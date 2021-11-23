<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('horario_id')->unsigned()->nullable()->unique();
            $table->integer('user_id')->unsigned();
            $table->integer('codigo_id')->unsigned()->nullable();
            $table->integer('servicio_id')->unsigned()->nullable();
            $table->foreign('horario_id')->references('id')->on('horarios')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('codigo_id')->references('id')->on('codigos')->onDelete('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->string('folio', 100);
            $table->float('precio_final')->nullable();
            $table->enum('state', array('draft','confirmend','finished', 'paid', 'canceled'))->default('draft');
            $table->softDeletes();
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
        Schema::dropIfExists('citas');
    }
}
