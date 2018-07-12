<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',150);
            $table->timestamps();
        });

        DB::table('status_notifications')->insert(
            array('title' => 'Mostrar MATCH a contraparte'));

        DB::table('status_notifications')->insert(
            array('title' => 'Mostrar MATCH a propietario'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_notifications');
    }
}