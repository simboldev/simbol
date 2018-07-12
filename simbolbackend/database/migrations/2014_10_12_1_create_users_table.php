<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('email')->unique();
            $table->boolean('estatususuario')->default(true);
            $table->boolean('recomendado_por_user_id');
            $table->timestamps();
            $table->integer('tipousuario_idtipousuario')->unsigned();

            $table->foreign('tipousuario_idtipousuario')->references('idtipousuario')->on('tipousuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
