<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('verification_token',40)->nullable();
            $table->integer('online')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('users', 'verification_token','online'))
        {
          Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verification_token');
            $table->dropColumn('online');
          });
        }
    }
}
