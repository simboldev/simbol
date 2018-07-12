<?php

namespace App\Http\Controllers;

use DB;
use App\log_user;
use Illuminate\Http\Request;

class LogUserController extends Controller
{
    var $code       = '';
    var $message    = '';
    var $data       = [];

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
        $log_data = $request->all();

        // Creamos las reglas de validación
        $rules = [
            'user_id'           => 'required|numeric',
            'posturas_match_id'  => 'required|numeric',
            'accion'            => 'required'
            ];

        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $validator = \Validator::make($log_data, $rules);
        if ($validator->fails())
        {
            $this->code       = "NOTOK";
            $this->message    = $validator->errors()->all();
        }
        else
        {
            $new_log = new \App\log_user($log_data);
            if( $new_log->save())
            {
                $this->code = "OK";
                $this->message    = "";
                $this->data       = $new_log->id;
            }
            else
            {
                $this->code       = "NOTOK";
                $this->message    = "Ocurrio un problema al intentar guardar el log";
            }
        }

        return response()->json([
            'code'=> $this->code,
            'message' => $this->message,
            'data'=> $this->data
        ],
        200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\log_user  $log_user
     * @return \Illuminate\Http\Response
     */
    public function show(log_user $log_user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\log_user  $log_user
     * @return \Illuminate\Http\Response
     */
    public function edit(log_user $log_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\log_user  $log_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, log_user $log_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\log_user  $log_user
     * @return \Illuminate\Http\Response
     */
    public function destroy(log_user $log_user)
    {
        //
    }

    public function por_usuario_y_postura($user_id, $postura_match_id)
    {
        $log_data = [];

        if( is_numeric($user_id) && is_numeric($postura_match_id))
        {
            $this->code = "OK";

            if($user_id != 0 && $postura_match_id != 0)
                $log_data = DB::table('log_users as log_u')->join('users as u', 'u.id', '=', 'log_u.user_id')->select('log_u.id','log_u.accion','u.id as user_id','u.username','log_u.created_at')->where('log_u.user_id','=',$user_id)->where('log_u.posturas_match_id','=',$postura_match_id)->orderBy('id', 'desc')->get();
            else if($user_id != 0 && $postura_match_id == 0)
                $log_data = DB::table('log_users as log_u')->join('users as u', 'u.id', '=', 'log_u.user_id')->select('log_u.id','log_u.accion','u.id as user_id','u.username','log_u.created_at')->where('log_u.user_id','=',$user_id)->orderBy('id', 'desc')->get();
            else if($user_id == 0 && $postura_match_id != 0)
                $log_data = DB::table('log_users as log_u')->join('users as u', 'u.id', '=', 'log_u.user_id')->select('log_u.id','log_u.accion','u.id as user_id','u.username','log_u.created_at')->where('log_u.posturas_match_id','=',$postura_match_id)->orderBy('id', 'desc')->get();

            $this->data = $log_data;

        }else{
            $this->code = "NOTOK";
            $this->message = "Parámetros requeridos (user_id y postura_match_id) deben ser valores numéricos";
        } 

        return response()->json([
            'code'=> $this->code,
            'message' => $this->message,
            'data'=> $this->data
        ],
        200);
    }
}
