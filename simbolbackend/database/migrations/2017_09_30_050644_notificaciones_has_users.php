<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificacionesHasUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notificaciones_has_users', function (Blueprint $table) {
            $table->increments('idnotificaciones_has_users');
            $table->integer('users_id')->unsigned();
            $table->integer('notificaciones_idnotificaciones')->unsigned();
        });

        Schema::table('notificaciones_has_users', function($table)
        {
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('notificaciones_idnotificaciones')->references('idnotificaciones')->on('notificaciones')->onDelete('cascade');
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
        Schema::dropIfExists('notificaciones_has_users');
    }
}
