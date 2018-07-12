<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPosturaMatchIdToNotificacionesHasUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notificaciones_has_users', function (Blueprint $table) {
            //
            $table->integer('postura_match_id')->unsigned()->nullable();
            
            $table->foreign('postura_match_id')->references('idPosturasMatch')->on('posturasMatches')->onDelete('cascade');
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
        Schema::table('notificaciones_has_users', function (Blueprint $table) {
            //
            $table->dropColumn('postura_match_id');
        });
    }
}
