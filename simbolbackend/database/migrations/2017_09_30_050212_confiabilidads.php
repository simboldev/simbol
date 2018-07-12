<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Confiabilidads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('confiabilidads', function (Blueprint $table) {
            $table->increments('idconfiabilidad');
            $table->integer('idusersolicitconfiab')->unsigned();
            $table->integer('iduserrecomconfiab')->unsigned();
            $table->boolean('estatus')->default(false);
            $table->string('comentario',500);
            $table->timestamps();
        });

        Schema::table('confiabilidads', function($table)
        {
            $table->foreign('idusersolicitconfiab')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('iduserrecomconfiab')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('confiabilidads');
    }
}