<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNegociacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('negociacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idbanco')->unsigned();
            $table->string('aba',100);
            $table->string('nrocuenta',100);
            $table->string('email',100);
            $table->string('nroidentificacion',100);
            $table->string('comprobante',500)->nullable();
            $table->integer('idposturamatch')->unsigned();
            $table->integer('estatusnegociacion');
            $table->integer('iduser')->unsigned();;
            $table->timestamps();
        });
        
        Schema::table('negociacions', function($table) {
            $table->foreign('idbanco')->references('idbancos')->on('bancos')->onDelete('cascade');
            $table->foreign('idposturamatch')->references('idposturasMatch')->on('posturas_matches')->onDelete('cascade');

            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('negociacions');
    }
}
