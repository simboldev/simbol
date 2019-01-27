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
      if (Schema::hasTable('posturasMatches'))
      {
        Schema::table('posturasMatches', function (Blueprint $table) {
            //
            $table->integer('estatusOperaciones_idestatusOperacion')->unsigned();
            
            $table->foreign('estatusOperaciones_idestatusOperacion')->references('idestatusOperacion')->on('estatusOperaciones')->onDelete('cascade');
        });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      if (Schema::hasTable('posturasMatches'))
      {
        Schema::table('posturasMatches', function (Blueprint $table) {
          $table->dropForeign(['estatusOperaciones_idestatusOperacion']);
          $table->dropColumn('estatusOperaciones_idestatusOperacion');
        });
      }
    }
}
