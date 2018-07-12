<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPosturasMatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posturasMatches', function (Blueprint $table) {
            //
            $table->boolean('acepta_user_propietario')->default(false);
            $table->boolean('acepta_user_contraparte')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posturasMatches', function (Blueprint $table) {
            //
            $table->dropColumn('acepta_user_propietario');
            $table->dropColumn('acepta_user_contraparte');
        });
    }
}
