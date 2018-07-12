<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPosturasMatchIdToLogUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('log_users', function (Blueprint $table) {
            //
            $table->integer('posturas_match_id')->default(0)->unsigned();

            $table->foreign('posturas_match_id')->references('idposturasMatch')->on('posturasMatches')->onDelete('cascade');
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
        Schema::table('log_users', function (Blueprint $table) {
            //
            $table->dropColumn('posturas_match_id');
        });
    }
}
