<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Amigos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('amigos', function (Blueprint $table) {
            $table->increments('idamigos');
            $table->integer('user1')->unsigned();
            $table->integer('user2')->unsigned();
            $table->timestamps();

        });

        Schema::table('amigos', function($table)
        {
            $table->foreign('user1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user2')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('amigos');
    }
}
