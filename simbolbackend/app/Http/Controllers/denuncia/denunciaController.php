<?php

namespace App\Http\Controllers\denuncia;

use DB;
use App\User;
use App\denuncia;
use App\posturasMatches;
use App\Mail\emailDenuncia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class denunciaController extends Controller
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

        $code       = "OK";
        $message    = "";
        $data       = [];

        //$denuncia = new \App\denuncia($request->all());
        
        $idposturasMatch = $request->paramPost;
        $estatusOperacion = 5;

        $denuncia = new \App\denuncia();
        $denuncia->idvictima = $request->idvictima;
        $denuncia->idvictimario = $request->idvictimario;
        $denuncia->nocumpletiempotransf = $request->nocumpletiempotransf;
        $denuncia->transfmontodif = $request->transfmontodif;
        $denuncia->transfnorecibida = $request->transfnorecibida;
        $denuncia->nocumplecondpreest = $request->nocumplecondpreest;
        $denuncia->detalle = $request->detalle;

        if($request->hasFile('evidencias')){
            $denuncia->evidencias = $request->file('evidencias')->store('evidenciasDenuncias');
        }


        $usuario = User::select('username','email')->where('id','=',$denuncia->idvictimario)->first();

        if($denuncia->save()){
            //se actualiza el estatus de la operacion de la postura match
            $query = DB::table('posturas_matches')
                ->where('idposturasMatch', $idposturasMatch)
                ->update(['estatusOperaciones_idestatusOperacion' => $estatusOperacion]);

            //envio de email
            // Mail::to($usuario->email)
            //         ->send(new emailDenuncia());
        }else{
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar registrar en la tabla denuncias";
        }
 
        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);
        
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
