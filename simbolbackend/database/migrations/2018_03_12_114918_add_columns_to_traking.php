<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTraking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('trackings', function (Blueprint $table) {
            //
            $table->integer('opsatisf1')->nullable();
            $table->integer('opsatisf2')->nullable();
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
        Schema::table('trackings', function (Blueprint $table) {
            //
            $table->dropColumn('opsatisf1');
            $table->dropColumn('opsatisf2');
        });
    }
}
