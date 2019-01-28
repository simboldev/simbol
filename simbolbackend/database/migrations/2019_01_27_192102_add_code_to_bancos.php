<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToBancos extends Migration
{
  public function up()
  {
    if(!Schema::hasColumn('bancos', 'code'))
    {
      Schema::table('bancos', function (Blueprint $table)
      {

        $table->string('code',10)->nullable()->after('idbancos');
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
    Schema::table('bancos', function (Blueprint $table) {
      $table->dropColumn('code');
    });
  }
}
