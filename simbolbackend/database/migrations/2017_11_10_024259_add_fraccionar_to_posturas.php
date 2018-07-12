<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFraccionarToPosturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posturas', function (Blueprint $table) {
            //
            $table->boolean('fraccionar')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posturas', function (Blueprint $table) {
            //
            $table->dropColumn('fraccionar');
        });
    }
}
