<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersHasPais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users_has_pais', function (Blueprint $table) {
            $table->increments('idusers_has_pais');
            $table->integer('users_id')->unsigned();
            $table->integer('pais_idpais')->unsigned();
            $table->timestamps();
            
        });

        Schema::table('users_has_pais', function($table)
        {
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pais_idpais')->references('idpais')->on('pais')->onDelete('cascade');
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
        Schema::dropIfExists('users_has_pais');
    }
    
}
