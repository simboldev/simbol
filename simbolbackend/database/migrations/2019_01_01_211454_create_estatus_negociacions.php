<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstatusNegociacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('estatus_negociacions', function (Blueprint $table) {
          $table->increments('id');
          $table->string('estatus',150);
          $table->timestamps();
      });

      // Insert estatus_negociacions 
      DB::table('estatus_negociacions')->insert([
        ['estatus' => 'Confirma que transfirió BsS','created_at'=> new \dateTime,'updated_at'=> new \dateTime],
        ['estatus' => 'Confirma que recibió Bs','created_at'=> new \dateTime,'updated_at'=> new \dateTime],
        ['estatus' => 'Confirma que transfirió USD','created_at'=> new \dateTime,'updated_at'=> new \dateTime],
        ['estatus' => 'Confirma que recibió USD','created_at'=> new \dateTime,'updated_at'=> new \dateTime],
        ['estatus' => 'Cambio realizado','created_at'=> new \dateTime,'updated_at'=> new \dateTime]
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('estatus_negociacions');
    }
}
