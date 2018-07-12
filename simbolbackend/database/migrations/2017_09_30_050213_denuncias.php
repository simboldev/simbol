<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Denuncias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('denuncias', function (Blueprint $table) {
            $table->increments('iddenuncias');
            $table->integer('idvictima')->unsigned();
            $table->integer('idvictimario')->unsigned();
            $table->string('motivo1',100)->nullable();
            $table->string('motivo2',100)->nullable();
            $table->string('motivo3',100)->nullable();
            $table->string('motivo4',100)->nullable();
            $table->string('detalle',500)->nullable();
            $table->binary('evidencias')->nullable();
            $table->date('fecha')->nullable();
            $table->boolean('estatusdenuncia')->default(false);
            $table->timestamps();

        });

        Schema::table('denuncias', function($table)
        {
            $table->foreign('idvictima')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('idvictimario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('denuncias');
    }
}
