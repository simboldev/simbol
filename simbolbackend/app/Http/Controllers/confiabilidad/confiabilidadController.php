<?php

namespace App\Http\Controllers\confiabilidad;
use DB;
use App\User;
use App\notificacion;
use Illuminate\Http\Request;
use App\Mail\emailConfiabilidad;
use App\notificaciones_has_users;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class confiabilidadController extends Controller
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

        $confiabilidad = new \App\confiabilidad();
        $idsesion = $request->idsesion;
        $confiabilidad->idusersolicitconfiab = $request->idusersolicitconfiab;
        $confiabilidad->iduserrecomconfiab = $request->iduserrecomconfiab;
        $confiabilidad->estatus = $request->estatus;
        $confiabilidad->comentario = $request->comentario;
        
        $usuario = User::select('username','email')->where('id','=',$confiabilidad->idusersolicitconfiab)->first();

        if($confiabilidad->save()){
            $data   =   $confiabilidad;

            //envio de email
            // Mail::to($usuario->email)
            //         ->send(new emailConfiabilidad());

            //envio de notificacion interna
            $respNotif=$this->saveNotify($idsesion,$request->iduserrecomconfiab,$request->idusersolicitconfiab);
            if($respNotif == true){
                    $resp=true;
            }else{
                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar guardar notificación";
            }
        }else{
             $code       = "NOTOK";
             $message    = "Ocurrio un problema al intentar registrar en la tabla confiabilidad";
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }


    //Método para guardar en la tabla notificaciones
    public function saveNotify($idusuarioC,$iduser,$idUserConfirma){
      
        $usuario = User::select('username')->where('id','=',$idusuarioC)->first();
        $usuarioAconfirmar = User::select('username')->where('id','=',$iduser)->first();
        
        $notificacion = new \App\notificacion();
        $not_has_user = new \App\notificaciones_has_users();

        $notificacion->titulo='Notificacion de Confiabilidad';
        $notificacion->cuerpo='El usuario '.$usuario->username.' solicita que confirmes la confiabilidad de '.$usuarioAconfirmar->username;
        $notificacion->adjunto='';
        $notificacion->notleida=0;
        $notificacion->status_notifications_id=1;

        if($notificacion->save()){
            $not_has_user->users_id = $idUserConfirma;
            $not_has_user->notificaciones_idnotificaciones=$notificacion->id;
            if($not_has_user->save()){
                return true;
            }else{
                return false;
            }
            
        }else{
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
