<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Trackings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('trackings', function (Blueprint $table) {
            $table->increments('idtracking');
            $table->boolean('transferi')->default(false);
            $table->boolean('metransfirieron')->default(false);
            $table->boolean('conformetransfiere')->default(false);
            $table->boolean('conformetransferido')->default(false);
            /*
                Columna "id" = foranea idposturassmatch se dejo asi porque con el nombre "idposturassmatch" generaba un error en su momento
            */
            $table->integer('id')->unsigned()->nullable();
            $table->integer('iduser')->unsigned()->nullable();
            $table->integer('iduser2')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('trackings', function($table)
        {
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('iduser2')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('trackings');
    }
}
