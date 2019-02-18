<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Temporary table
      if (!Schema::hasTable('rate_ranges'))
      {
        Schema::create('rate_ranges', function (Blueprint $table) {
          $table->increments('id');
          $table->string('initial_amount',100);
          $table->string('final_amount',100);
          $table->timestamps();
        });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_ranges');
    }
}
