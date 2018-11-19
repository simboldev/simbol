<?php

namespace App\Http\Controllers\negociacion;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\negociacion;
use App\postura;
use App\posturasMatches;

class negociacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code       = "OK";
        $message    = "";
        $data       = [];

        $dataNegociacion = posturasMatches::select(
            'posturas_matches.idposturasMatch as idneg',
            DB::raw("IF(posturas_matches.estatusOperaciones_idestatusOperacion = '4','procesado','en proceso') as estatus"),
            'posturas_matches.created_at as fecha',
            'posturas.tasacambio as tasa',
            'posturas.quiero as divisa1',
            'posturas.tengo as divisa2'
        )
        ->join('posturas','posturas.idposturas','posturas_matches.posturas_idposturas')
        ->join('negociacions','negociacions.idposturamatch','posturas_matches.idposturasMatch')
        ->whereIn('negociacions.estatusnegociacion',[3,4])
        ->whereNotIn('posturas_matches.estatusOperaciones_idestatusOperacion',[0])
        ->orderBy('posturas_matches.idposturasMatch','DESC')
        ->get();

        $data = $dataNegociacion;

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);
        
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
        $code       = "OK";
        $message    = "";
        $data       = [];

        $negociacion = negociacion::select(
            'negociacions.id as idneg',
            'negociacions.idbanco',
            'negociacions.aba',
            'negociacions.nrocuenta',
            'negociacions.email',
            'negociacions.nroidentificacion',
            'negociacions.comprobante',
            'negociacions.estatusnegociacion',
            'negociacions.iduser',
            'bancos.nombre as nombrebanco'   
        )
        ->join('bancos','bancos.idbancos','negociacions.idbanco')
        ->where('negociacions.idposturamatch',$id)
        ->get();
        
        $data = $negociacion;

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

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

        $comprobante = negociacion::select('comprobante')
                        ->where('idposturamatch',$idPosturaMatch)
                        ->where('iduser','!=',$iduser)
                        ->first();

        $existNeg = negociacion::select(
                        'negociacions.id',
                        'negociacions.estatusnegociacion',
                        'negociacions.iduser',
                        'bancos.nombre as banco',
                        'negociacions.nrocuenta',
                        'negociacions.email',
                        'negociacions.nroidentificacion',
                        'negociacions.comprobante'
        )
        ->join('bancos','bancos.idbancos','negociacions.idbanco')
        ->where('idposturamatch',$idPosturaMatch)
        ->where('iduser',$iduser)
        ->first();
        


        if($existNeg == null){
            $data = ['estatusNeg'=>0,'iduser'=>'no','moneda'=>''];
        }else if(count($existNeg) == 1){
            if(!$comprobante){
                $data = [
                        'idNeg' => $existNeg->id,
                        'estatusNeg'=> $existNeg->estatusnegociacion,
                        'iduser'=> $existNeg->iduser,
                        'moneda'=> $qmoneda,
                        'banco' => $existNeg->banco,
                        'nrocuenta' => $existNeg->nrocuenta,
                        'email' =>  $existNeg->email,
                        'nroidentificacion' => $existNeg->nroidentificacion,
                        'comprobante'   => ''
                ];
            }else{
                    $data = [
                        'idNeg' => $existNeg->id,
                        'estatusNeg'=> $existNeg->estatusnegociacion,
                        'iduser'=> $existNeg->iduser,
                        'moneda'=> $qmoneda,
                        'banco' => $existNeg->banco,
                        'nrocuenta' => $existNeg->nrocuenta,
                        'email' =>  $existNeg->email,
                        'nroidentificacion' => $existNeg->nroidentificacion,
                        'comprobante'   => $comprobante->comprobante
                    ];
            }
            
        }/*else if(count($existNeg) == 2){
            $data = [
                        'idNeg' => $existNeg->id,
                        'estatusNeg'=> $existNeg->estatusnegociacion,
                        'iduser'=> $existNeg->iduser,
                        'moneda'=> $qmoneda,
                        'banco' => $existNeg->banco,
                        'nrocuenta' => $existNeg->nrocuenta,
                        'email' =>  $existNeg->email,
                        'nroidentificacion' => $existNeg->nroidentificacion,
                        'comprobante'   => $comprobante->comprobante
            ];
        }*/


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
            $negociacion->nroidentificacion = $identificacion;
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
            $negociacion->nroidentificacion = $identificacion;
            $negociacion->idposturamatch = $idposturamatchNeg;
            $negociacion->estatusnegociacion = 2;
            $negociacion->iduser = $iduser;

            $data = ['estatusNeg'=>2];
        } 

        if($negociacion->save()){
            
            $lastNeg = negociacion::select('id')
                ->where('idposturamatch',$idposturamatchNeg)
                ->where('iduser','!=',$iduser)
                ->get()
                ->last();

            if(count($lastNeg) != 0){
                $modEstatus = negociacion::where('id',$lastNeg->id)
                    ->update(['estatusnegociacion'=>2]);
            }

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

    public function saveComprobante(Request $request){

            $code       = "OK";
            $message    = "";
            $data       = [];

            $idNegociacion = $request->idNeg;

            if($request->hasFile('comprobante')){
                $comprobante = $request->file('comprobante')->store('evidenciasNegociacion');
            }

            if($query = negociacion::where('id',$idNegociacion)
                ->update([
                    'comprobante' => $comprobante,
                    'estatusnegociacion' => 3
                ]) 
            ) {

                $code       = "OK";
                $message    = "El comprobante se subió de forma exitosa";
                $data = 1;

            }else{
                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar guardar el archivo";
                $data = 0;
            }  
            

            return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
            ],200);
        
    }

    public function saveComprobanteContraparte(Request $request){
            $code       = "OK";
            $message    = "";
            $data       = [];

            $idNegociacion = $request->idNeg;
            $idUser = $request->idUser;

            if($request->hasFile('comprobante')){
                $comprobante = $request->file('comprobante')->store('evidenciasNegociacion');
            }

            if($query = negociacion::where('id',$idNegociacion)->where('iduser',$idUser)
                ->update([
                    'comprobante' => $comprobante,
                    'estatusnegociacion' => 5
                ]) 
            ) {

                $code       = "OK";
                $message    = "El comprobante se subió de forma exitosa";
                $data = 1;

            }else{
                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar guardar el archivo";
                $data = 0;
            }  

            return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
            ],200);

    }

    public function confirmacion1($iduser,$idposturamatch){
            
            $code       = "OK";
            $message    = "";
            $data       = [];

            if(
                    negociacion::where('idposturamatch',$idposturamatch)
                            ->where('iduser',$iduser)
                            ->update(['estatusnegociacion' => 3])
            ){
                $code       = "OK";
                $message    = "El estatus se ha actualizado de forma exitosa";
                $data = 1;
            }else{

                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar actualizar el registro";
                $data = 0;
            
            }  

            return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
            ],200);

    }

    public function confirmacion2($iduser,$idposturamatch){
            
            $code       = "OK";
            $message    = "";
            $data       = [];

            if(
                    negociacion::where('idposturamatch',$idposturamatch)
                            ->where('iduser',$iduser)
                            ->update(['estatusnegociacion' => 4])
            ){
                $code       = "OK";
                $message    = "El estatus se ha actualizado de forma exitosa";
                $data = 1;
            }else{

                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar actualizar el registro";
                $data = 0;
            
            }  

            return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
            ],200);

    }

    public function confirmacion3($iduser,$idposturamatch){
            
            $code       = "OK";
            $message    = "";
            $data       = [];

            if(
                    negociacion::where('idposturamatch',$idposturamatch)
                            ->where('iduser',$iduser)
                            ->update(['estatusnegociacion' => 5])
            ){
                $code       = "OK";
                $message    = "El estatus se ha actualizado de forma exitosa";
                $data = 1;
            }else{

                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar actualizar el registro";
                $data = 0;
            
            }  

            return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
            ],200);

    }


    public function confirmacion4($iduser,$idposturamatch){
            
            $code       = "OK";
            $message    = "";
            $data       = [];

            if(
                    negociacion::where('idposturamatch',$idposturamatch)
                            ->update(['estatusnegociacion' => 6])
            ){
                $code       = "OK";
                $message    = "El estatus se ha actualizado de forma exitosa";
                $data = 1;
            }else{

                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar actualizar el registro";
                $data = 0;
            
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
