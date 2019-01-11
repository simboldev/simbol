<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFksNegociacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('negociacions', function($table)
      {
          $table->foreign('estatusnegociacion')->references('id')->on('estatus_negociacions')->onDelete('cascade');

          $table->foreign('estatus_autoriza_backoffice')->references('id')->on('estatus_negociacions')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {      
      Schema::table('negociacions', function($table)
      {
          $table->drop_foreign('estatusnegociacion')->references('id')->on('estatus_negociacions')->onDelete('cascade');

          $table->drop_foreign('estatus_autoriza_backoffice')->references('id')->on('estatus_negociacions')->onDelete('cascade');
      });
    }
}
