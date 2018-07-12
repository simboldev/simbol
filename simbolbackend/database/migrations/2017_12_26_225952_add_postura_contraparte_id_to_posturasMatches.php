<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPosturaContraparteIdToPosturasMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try
        {
            Schema::table('posturasMatches', function($table)
            {
                $table->integer('postura_contraparte_id')->unsigned();

                $table->foreign('postura_contraparte_id')->references('idposturas')->on('posturas')->onDelete('cascade');
            });            
        }
        catch (Exception $e)
        {
            error_log('==== ERROR MIGRATION ADD_POSTURA_CONTRAPARTE ====');
            error_log($e);
        }
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
            $table->dropColumn('postura_contraparte_id');
        });
    }
}
