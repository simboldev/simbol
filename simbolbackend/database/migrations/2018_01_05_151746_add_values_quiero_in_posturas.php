<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValuesQuieroInPosturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $posturas = DB::table('posturas as p')
            ->select('p.*')
            ->get();
            error_log('==== AddValuesQuieroInPosturas ==== ');
            error_log(count($posturas));
        if( count($posturas) > 0)
        {
            foreach ($posturas as $postura)
            {
                if($postura->quiero_moneda_id == 1) //BsF
                {
                    error_log('if');
                    $postura->quiero = ($postura->tengo*$postura->tasacambio);
                }
                else if($postura->quiero_moneda_id == 2) // USD
                {
                    error_log('else');
                    if($postura->tasacambio > 0)
                        $postura->quiero = ($postura->tengo/$postura->tasacambio);
                }
                error_log($postura->quiero);
                $postura->quiero = number_format((float)$postura->quiero, 2, '.', '');

                error_log($postura->quiero);
                DB::table('posturas')
                    ->where('idposturas','=',$postura->idposturas)
                    ->update(['quiero' => $postura->quiero]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
