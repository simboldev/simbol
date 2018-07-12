<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstatusOperacionIdestatusOperacionToPosturasMatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posturasMatches', function (Blueprint $table) {
            //
            $table->integer('estatusOperaciones_idestatusOperacion')->unsigned();
            
            $table->foreign('estatusOperaciones_idestatusOperacion')->references('idestatusOperacion')->on('estatusOperaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posturasMatches', function (Blueprint $table) {
            //
            $table->dropColumn('estatusOperaciones_idestatusOperacion');
        });
    }
}
