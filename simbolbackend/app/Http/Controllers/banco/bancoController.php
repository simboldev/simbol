<?php

namespace App\Http\Controllers\banco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\banco;
use App\posturasMatches;
use App\postura;
use App\bancos_pais_monedas;

class bancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    public function consBancoPostura($idPosturaMatch){
        
        $code       = "OK";
        $message    = "Success";

        $data       =   banco::select(
                            'bancos.idbancos',
                            'bancos.nombre'
                        )
                        ->join('bancos_pais_monedas','bancos_pais_monedas.idbanco','bancos.idbancos')
                        ->join('posturas','posturas.quiero_moneda_id','bancos_pais_monedas.idmoneda')
                        ->join('posturas_matches','posturas_matches.posturas_idposturas','posturas.idposturas')
                        ->where('posturas_matches.idposturasMatch',$idPosturaMatch)
                        ->get();

        return response()->json([
            'code'=> $code,
            'message' => $message,
            'data'=> $data
        ],
        200);
        

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
