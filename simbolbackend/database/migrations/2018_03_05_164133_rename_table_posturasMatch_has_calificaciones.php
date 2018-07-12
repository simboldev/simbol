<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTablePosturasMatchHasCalificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::rename('posturasMatch_has_calificaciones', 'posturas_match_has_calificaciones');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::rename('posturas_match_has_calificaciones', 'posturasMatch_has_calificaciones');
    }
}
