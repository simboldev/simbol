<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Chats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('idchat');
            $table->integer('iduser')->unsigned();
            $table->string('username',50);
            $table->string('textouser',500)->nullable();
            $table->string('adjuntouser',500)->nullable();
            $table->timestamps();
            $table->integer('posturasMatches_idposturasMatch')->unsigned();

        });

        Schema::table('chats', function($table)
        {
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('posturasMatches_idposturasMatch')->references('idposturasMatch')->on('posturasMatches')->onDelete('cascade');
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
        Schema::dropIfExists('chats');
    }
}
