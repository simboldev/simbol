<?php

namespace App\Http\Controllers\calificaciones;

use App\User;
use App\notificacion;
use App\calificaciones;
use Illuminate\Http\Request;
use App\notificaciones_has_users;
use App\Http\Controllers\Controller;
use App\posturasMatch_has_calificaciones;

class calificacionesController extends Controller
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
        $requestt = file_get_contents('php://input');
        $dat = json_decode($requestt);

        $code       = "OK";
        $message    = "";
        $data       = [];

        $calificaciones = new \App\calificaciones();
        $calificaciones->puntos = $request->puntos;
        $calificaciones->comentario = $request->comentario;
        $calificaciones->iduser = $request->iduser;
        $calificaciones->idusuariocalificado = $request->idusuariocalificado;
        $calificaciones->idPosturasMatch = $request->idPosturasMatch;

        if( $calificaciones->save())
        {
            $data = $calificaciones;
            $califUser = $this->savePostAndCalif($request->idPosturasMatch,$data->id);
            $usuario = User::select('username')->where('id','=',$data->iduser)->get();
            $notificacion = new \App\notificacion();
            $not_has_user = new \App\notificaciones_has_users();

            $notificacion->titulo='Notificacion de Calificacion';
            $notificacion->cuerpo='Estimado usuario se le notifica que el usuario '.$usuario[0]->username.' lo ha calificado con '.$data->puntos.' estrellas por la operacion culminada';
            $notificacion->adjunto='';
            $notificacion->notleida=0;
            $notificacion->status_notifications_id=1;

            if($notificacion->save()){
                $not_has_user->users_id = $data->idusuariocalificado;
                $not_has_user->notificaciones_idnotificaciones=$notificacion->id;
                $not_has_user->postura_match_id = $calificaciones->idPosturasMatch; 

                if($not_has_user->save()){
                    $dat = $data->puntos;
                    return $dat;
                }else{
                    return $dat;
                }

            }else{
                return $dat;
            }
        }
        else
        {

            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar guardar calificaciones";         
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $dat
        ],200);
        
    }

    //Métdo que guarda sobre la tabla posturasMatch_has_calificaciones
    public function savePostAndCalif($idPosturaMatch,$idcalificacion){

        $califPosturas = new \App\posturasMatch_has_calificaciones(); 
        $califPosturas->idposturasMatch = $idPosturaMatch;
        $califPosturas->idcalificaciones = $idcalificacion;
        

        if($califPosturas->save()){
            //$data = $califPosturas;
            return true;
        }
        else
        {
            return false;

        }

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

    //método de consulta por idposturamatch
    public function califXidPmatch($idp){
        $code       = "OK";
        $message    = "";
        $data       = [];

        if( is_numeric($idp))
        {
            $data = calificaciones::select('idPosturasMatch')
                                ->where('idPosturasMatch','=',$idp)
                                ->get();
           //se valida si idpostura match esta dos veces en la tabla, si esta dos veces en la tabla el valor que debe devolver es true                     
           if(count($data) == 2){
                $data=true;
           }else{
                $data=false;
           }
        }
        else
        {
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        return response()->json([
            'code'      => $code,
            'message'   => $message,
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
