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
      if(!Schema::hasColumn('negociacions', 'estatusnegociacion','estatus_autoriza_backoffice'))
      {
        Schema::table('negociacions', function (Blueprint $table)
        {

          $table->foreign('estatusnegociacion')->references('id')->on('estatus_negociacions')->onDelete('cascade');

          $table->foreign('estatus_autoriza_backoffice')->references('id')->on('estatus_negociacions')->onDelete('cascade');
        });
      }
    }
}
