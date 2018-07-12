<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIdStatusNotificationsToNotificacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notificacions', function (Blueprint $table) {
            //
            $table->integer('status_notifications_id')->unsigned()->default(1);
            
            $table->foreign('status_notifications_id')->references('id')->on('status_notifications')->onDelete('cascade');
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
        Schema::table('notificacions', function (Blueprint $table) {
            //
            $table->dropColumn('status_notifications_id');
        });
    }
}
