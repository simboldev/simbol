<?php

namespace App\Http\Controllers\operacion;

use App\User;
use App\notificacion;
use Illuminate\Http\Request;
use App\notificaciones_has_users;
use App\Mail\emailCancelaOperacion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class operacionController extends Controller
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

    //metodo para cancelar operaciones
    public function cancelaOperation($id,$username,$idPosturaMatch)
    {

        $code       = "OK";
        $message    = "";
        $data       = [];

        $notificacion = new \App\notificacion();
        $not_has_user = new \App\notificaciones_has_users();

        
            $notificacion->titulo='Notificacion de Cancelacion de Operacion';
            $notificacion->cuerpo='Estimado usuario se le notifica que el usuario '.$username.' ha cancelado la operacion';
            $notificacion->adjunto='';
            $notificacion->notleida=0;
            $notificacion->status_notifications_id =1;

            if($notificacion->save()){
                $not_has_user->users_id = $id;
                $not_has_user->notificaciones_idnotificaciones=$notificacion->id;
                $not_has_user->postura_match_id=$idPosturaMatch;

                if($not_has_user->save()){
                    $code = "OK";
                    $message = "el registro se guardo con exito en not_has_user";
                    $data       = true;

                    //notificacion via email
                    // $email = User::select('email')->where('id','=',$id)->get();
                    // $emailContraparte = User::select('email')->where('username','=',$username)->get();
                    // Mail::to($email)
                    //     ->send(new emailCancelaOperacion());
                    
                    // Mail::to($emailContraparte)
                    //     ->send(new emailCancelaOperacion());
                        
                }else{
                    $code = "NOTOK";
                    $message = "Error guardando en not_has_user";
                    $data       = [];
                }

            }else{
                 $code = "NOTOK";
                 $message = "Error guardando en not_has_user";
                 $data       = [];
            }

            

        return response()->json([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data
        ],
        200);

    }

    //metodo para denunciar operacion
    public function denunciarOperacion()
    {
        $code       = "OK";
        $message    = "";
        $data       = [];

        $notificacion = new \App\notificacion();
        $not_has_user = new \App\notificaciones_has_users();




         return response()->json([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data
        ],
        200);
        
    }


}
