<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNotLeidaToNotificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notificaciones', function (Blueprint $table) {
            //
            $table->integer('NotLeida');
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
        Schema::table('notificaciones', function (Blueprint $table) {
            //
            $table->dropColumn('NotLeida');
        });
    }
}
