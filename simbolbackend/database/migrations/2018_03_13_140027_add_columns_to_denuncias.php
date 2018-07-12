<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDenuncias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('denuncias', function (Blueprint $table) {
            //
            $table->string('nocumpletiempotransf',200)->nullable();
            $table->string('transfmontodif',200)->nullable();
            $table->string('transfnorecibida',200)->nullable();
            $table->string('nocumplecondpreest',200)->nullable(); //no cumple con las condiciones pre establecida
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
        Schema::table('denuncias', function (Blueprint $table) {
            //
            $table->dropColumn('nocumpletiempotransf');
            $table->dropColumn('transfmontodif');
            $table->dropColumn('transfnorecibida');
            $table->dropColumn('nocumplecondpreest');
        });
    }
}
