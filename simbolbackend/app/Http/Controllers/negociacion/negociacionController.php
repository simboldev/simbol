<?php

namespace App\Http\Controllers\negociacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\negociacion;
use App\postura;

class negociacionController extends Controller
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

    public function consultNeg($idPosturaMatch,$iduser){

        $code       = "OK";
        $message    = "";
        $data       = [];
        
        $datUserMon = postura::select('posturas.quiero_moneda_id')
                        ->join('posturas_matches','posturas_matches.posturas_idposturas','posturas.idposturas')
                        ->where('posturas.iduser',$iduser)
                        ->where('posturas_matches.idposturasMatch',$idPosturaMatch)
                        ->first();

        if(count($datUserMon) == 0){
            $qmoneda = '';
        }else{
            $qmoneda = $datUserMon->quiero_moneda_id;
        }

        $existNeg = negociacion::select('estatusnegociacion','iduser')
                        ->where('idposturamatch',$idPosturaMatch)
                        ->first();
     
        if($existNeg == null){
            $data = ['estatusNeg'=>0,'iduser'=>'','moneda'=>$qmoneda];
        }else if(count($existNeg) == 1){
            $data = ['estatusNeg'=>$existNeg->estatusnegociacion,'iduser'=>$existNeg->iduser,'moneda'=>$qmoneda];
        }


        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }

    public function saveNegociacion($idbancoNeg,$abaNeg,$nrocuentaNeg,$emailNeg,$nacionalidadNeg,$nroidentificacionNeg,$idposturamatchNeg,$iduser){
        
        $code       = "OK";
        $message    = "";
        $data       = [];

        $identificacion = $nacionalidadNeg.$nroidentificacionNeg;

        $existNeg = negociacion::select('estatusnegociacion')
            ->where('idposturamatch',$idposturamatchNeg)
            ->get(); 

        if(count($existNeg) == 0){
            $negociacion = new \App\negociacion();
            $negociacion->idbanco = $idbancoNeg;
            $negociacion->aba = $abaNeg;
            $negociacion->nrocuenta = $nrocuentaNeg;
            $negociacion->email = $emailNeg;
            $negociacion->nroidentificacion = $nroidentificacionNeg;
            $negociacion->idposturamatch = $idposturamatchNeg;
            $negociacion->estatusnegociacion = 1;
            $negociacion->iduser = $iduser;

            $data = ['estatusNeg'=>1];

        }else{
            $negociacion = new \App\negociacion();
            $negociacion->idbanco = $idbancoNeg;
            $negociacion->aba = $abaNeg;
            $negociacion->nrocuenta = $nrocuentaNeg;
            $negociacion->email = $emailNeg;
            $negociacion->nroidentificacion = $nroidentificacionNeg;
            $negociacion->idposturamatch = $idposturamatchNeg;
            $negociacion->estatusnegociacion = 2;
            $negociacion->iduser = $iduser;

            $data = ['estatusNeg'=>2];
        } 

        if($negociacion->save()){
            $message = "El registro se ha guardado con exito";
        }else{
            $message = "El registro no se ha podido guardar con exito";
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

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
