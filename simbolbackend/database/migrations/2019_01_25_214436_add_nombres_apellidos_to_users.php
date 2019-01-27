<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNombresApellidosToUsers extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table)
    {
      $table->string('nombres',150)->nullable()->after('username');
      $table->string('apellidos', 150)->nullable()->after('nombres');

      if(!Schema::hasColumn('users', 'recomendado_por_user_id'))
      {
        $table->integer('recomendado_por_user_id')->change()->nullable();
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
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('nombres');
      $table->dropColumn('apellidos');
    });
  }
}
