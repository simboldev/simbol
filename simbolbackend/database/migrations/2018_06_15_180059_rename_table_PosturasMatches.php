<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTablePosturasMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      //
      if (Schema::hasTable('posturasMatches'))
      {
        Schema::rename('posturasMatches', 'posturas_matches');
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasTable('posturasMatches'))
        {
          Schema::rename('posturasMatches', 'posturas_matches');
        }
    }
}
