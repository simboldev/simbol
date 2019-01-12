<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToNegociacions extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('negociacions', function (Blueprint $table) {
        $table->integer('estatus_autoriza_backoffice')->unsigned()->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('negociacions', function (Blueprint $table){
        $table->dropColumn('estatus_autoriza_backoffice');
    });
  }
}
