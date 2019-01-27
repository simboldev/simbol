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
      Schema::table('log_users', function (Blueprint $table)
      {
        if(!Schema::hasColumn('log_users', 'posturas_match_id'))
        {
          $table->integer('posturas_match_id')->default(0)->unsigned();

          $table->foreign('posturas_match_id')->references('idposturasMatch')->on('posturasMatches')->onDelete('cascade');
        }
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
        if(Schema::hasColumn('log_users', 'posturas_match_id'))
        {
          Schema::table('log_users', function (Blueprint $table) {
            $table->dropForeign(['posturas_match_id']);
            $table->dropColumn('posturas_match_id');
          });
        }
    }
}
