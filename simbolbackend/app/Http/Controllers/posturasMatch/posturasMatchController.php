<?php

namespace App\Http\Controllers\posturasMatch;

use DB;
use DateTime;
use App\postura;
use App\posturasMatches;
use App\User;
use App\notificacion;
use App\notificaciones_has_users;
use App\log_user;
use App\estatusOperacion;
use App\moneda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class posturasMatchController extends Controller
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
        $data = [];
        $postura_match_data = $request->all();

        // Creamos las reglas de validación
        $rules = [
            'posturas_idposturas'   => 'required',
            'postura_contraparte_id' => 'required',
            'estatusMatch'          => 'required',
            'users_idusers'         => 'required',
            'iduser2'               => 'required',
            'estatusOperaciones_idestatusOperacion' => 'required',
            'cronometro' => 'required',
            'acepta_user_propietario' => 'required'
            ];

        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $validator = \Validator::make($postura_match_data, $rules);
        if ($validator->fails())
        {
            $code       = "NOTOK";
            $message    = $validator->errors()->all();
        }
        else
        {
            $postura_match = new \App\posturasMatches($postura_match_data);
            if( $postura_match->save())
            {
                $code       = "OK";
                $message    = "";
                $data       = $postura_match->idposturasMatch;

                $usuario = User::where('id','=',$postura_match->users_idusers)->get();

                $usuario_contraparte = User::where('id','=',$postura_match->iduser2)->get();

                $tittle_noti = 'Match';
                $text_noti = strtoupper($usuario_contraparte[0]->username).' el usuario '.strtoupper($usuario[0]->username).' ha hecho match con tu postura.';

                if( !$this->save_notification($usuario_contraparte[0]->id,$tittle_noti, $text_noti, $postura_match->idposturasMatch,1))
                {
                    $message    = "Se ha guardado la postura pero no se ha podido alamacenar la notificacion del match para la contraparte";
                }
            }
            else
            {
                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar guardar la postura match";
            }
        }

        return response()->json([
            'code'=> $code,
            'message' => $message,
            'data'=> $data
        ],
        200);
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

        if( is_numeric($id))
        {
            $posturasMatch = posturasMatches::where('idposturasMatch','=',$id)->get();
        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        if( is_numeric($id))
        {
            $posturas_matches = DB::table('posturas_matches as p_match')
                    ->join('posturas as post', 'p_match.posturas_idposturas', '=', 'post.idposturas')
                    ->select('p_match.*')
                    ->where('idposturasMatch','=',$id)
                    ->orderBy('p_match.idposturasMatch','desc')
                    // ->where('p_match.estatusOperaciones_idestatusOperacion','>=', 3)
                    ->get();

            foreach ($posturas_matches as $postura_match)
            {
                $postura_match->estatus = estatusOperacion::select('estatus')->where('idestatusOperacion','=',$postura_match->estatusOperaciones_idestatusOperacion)->get()[0]->estatus;

                $postura_match->postura = postura::where('idposturas','=',$postura_match->posturas_idposturas)->get();

                $postura_match->postura_contraparte = postura::where('idposturas','=',$postura_match->postura_contraparte_id)->get();

                $postura_match->postura[0]->usuario = User::select('id','username')->where('id','=',$postura_match->users_idusers)->get()[0];

                $postura_match->postura[0]->usuario->log = $this->log_por_usuario_y_postura($postura_match->users_idusers, $postura_match->idposturasMatch);

                $postura_match->postura[0]->quiero_moneda_simbolo = 
                    moneda::select('admin_simbolo')->where('idmonedas','=',$postura_match->postura[0]->quiero_moneda_id)->get()[0]['admin_simbolo'];

                $postura_match->postura[0]->tengo_moneda_simbolo = 
                    moneda::select('admin_simbolo')->where('idmonedas','=',$postura_match->postura[0]->tengo_moneda_id)->get()[0]['admin_simbolo'];

                $postura_match->postura_contraparte[0]->usuario = User::select('id','username')->where('id','=',$postura_match->iduser2)->get()[0];

                $postura_match->postura_contraparte[0]->usuario->log = $this->log_por_usuario_y_postura($postura_match->iduser2, $postura_match->idposturasMatch);

                $postura_match->postura_contraparte[0]->quiero_moneda_simbolo = 
                    moneda::select('admin_simbolo')->where('idmonedas','=',$postura_match->postura_contraparte[0]->quiero_moneda_id)->get()[0]['admin_simbolo'];

                $postura_match->postura_contraparte[0]->tengo_moneda_simbolo = 
                    moneda::select('admin_simbolo')->where('idmonedas','=',$postura_match->postura_contraparte[0]->tengo_moneda_id)->get()[0]['admin_simbolo'];

                $post_calificaciones = DB::table('posturas_match_has_calificaciones')->where('idposturasMatch','=',$postura_match->idposturasMatch)->get();

                $postura_match->log = $this->log_por_usuario_y_postura(0, $postura_match->idposturasMatch);

                if (count($post_calificaciones) > 0)
                {
                    foreach($post_calificaciones as $post_cal)
                    {
                        $calificacion = DB::table('calificaciones')->select('*')->where('idcalificaciones','=',$post_cal->idcalificaciones)->get()[0];
                        
                        if( $calificacion->idusuariocalificado == $postura_match->users_idusers)
                        {
                            $postura_match->postura[0]->usuario->calificacion = $calificacion;
                        }
                        else if( $calificacion->idusuariocalificado == $postura_match->iduser2)
                        {
                            $postura_match->postura_contraparte[0]->usuario->calificacion = $calificacion;
                        }
                    }
                }
            }
        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $posturas_matches
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
    public function update(Request $request, posturasMatches $posturasMatches)
    {
        error_log('============== posturas_macth_update==============');
        //
        $code       = "OK";
        $message    = "";
        $data       = [];
        $flag_acepta_user_contraparte = 0;

        $posturasMatch = posturasMatches::where('idposturasMatch','=',$request->idposturasMatch)->get();

        $posturasMatch[0]->estatusMatch= $request->estatusMatch;
        $posturasMatch[0]->cronometro = $request->cronometro;
        $posturasMatch[0]->confiabilidads_idconfiabilidad = $request->confiabilidads_idconfiabilidad;
        $posturasMatch[0]->denuncias_iddenuncias = $request->denuncias_iddenuncias;
        $posturasMatch[0]->trackings_idtracking = $request->trackings_idtracking;
        $posturasMatch[0]->estatusOperaciones_idestatusOperacion = $request->estatusOperaciones_idestatusOperacion;
        $posturasMatch[0]->acepta_user_propietario = $request->acepta_user_propietario;

        if( $posturasMatch[0]->acepta_user_contraparte != $request->acepta_user_contraparte)
        {
            $posturasMatch[0]->acepta_user_contraparte = $request->acepta_user_contraparte;
            $flag_acepta_user_contraparte = 1;
        }
        
        
        if($posturasMatch[0]->update()){
            $data       = $posturasMatch;
            // Enviar email de actualizacion de postura
            // Mail::to($data[0]->email)
            //         ->send(new emailRecPassSuccess());
            $usuario = User::where('id','=',$posturasMatch[0]->users_idusers)->get();

            $usuario_contraparte = User::where('id','=',$posturasMatch[0]->iduser2)->get();

            if( $flag_acepta_user_contraparte == 1)
            {
                $this->save_log_user($usuario[0]->id,$posturasMatch[0]->idposturasMatch,"Inicia negociación");

                $this->save_log_user($usuario_contraparte[0]->id,$posturasMatch[0]->idposturasMatch,"Inicia negociación");

                $tittle_noti = 'Match activo';
                $text_noti = strtoupper($usuario[0]->username).' el usuario '.strtoupper($usuario_contraparte[0]->username).' ha aceptado el match con tu postura.';

                if( !$this->save_notification($usuario[0]->id,$tittle_noti, $text_noti, $posturasMatch[0]->idposturasMatch,2))
                {
                    $message    = "Se han actualizado los datos de la postura pero no se ha podido alamacenar la notificacion del match para la contraparte";
                }
            }

        }else{
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar actualizar la posturas match. ( "+$posturasMatch[0]->getErrors()+" )";
        }

        return response()->json([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data
        ],200);

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

    //Método para consultar montos X Posturas
    public function montosXposturas($idposturasMatch,$iduser){
        
        $code       = "OK";
        $message    = "Success";
        $data       = [];

        if(is_numeric($idposturasMatch)){
            $posturasMatch = posturasMatches::select('posturas_idposturas','postura_contraparte_id')
                        ->where('idposturasMatch','=',$idposturasMatch)
                        ->get();              
            
            $posturas = postura::select(
                    'idposturas',
                    'tasacambio',
                    'tengo',
                    'tengo_moneda_id',
                    'quiero_moneda_id',
                    'iduser'
                )
                ->where('idposturas','=',$posturasMatch[0]->posturas_idposturas)
                ->orWhere('idposturas','=',$posturasMatch[0]->postura_contraparte_id)
                ->where('iduser','=',$iduser)
                ->get();
            $data = $posturas;
        
        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (idposturasMatch) debe ser un valor numérico";
        }
        
        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }

    //Método para guardar notificaciones
    private function save_notification($iduser,$tittle, $text,$postura_match_id,$status){
        // error_log("save_notification "+$iduser+"  - "+$tittle+"  - "+$text+"   -   "+$postura_match_id);
        error_log("..................save_notification.................. ");

        $resp = false;
        $notificacion = new \App\notificacion();
        $not_has_user = new \App\notificaciones_has_users();

        $notificacion->titulo  = $tittle;
        $notificacion->cuerpo = $text;        
        $notificacion->adjunto  = '';
        $notificacion->notleida = 0;
        $notificacion->status_notifications_id = $status;
        
        if($notificacion->save())
        {
            $not_has_user->users_id = $iduser;
            $not_has_user->notificaciones_idnotificaciones=$notificacion->id;
            $not_has_user->postura_match_id = $postura_match_id;
            
            if($not_has_user->save()){
                $resp = true;
            }            
        }
        return $resp;
    }

    public function postura_match_por_posturas($idpostura, $idpostura_contraparte)
    {
        $this->code = "NOTOK";
        $posturasMatch = array();

        if( is_numeric($idpostura) && is_numeric($idpostura_contraparte))
        {
            if($idpostura > 0 && $idpostura_contraparte > 0)
            {
                $posturasMatch = posturasMatches::where('posturas_idposturas','=',$idpostura)->orWhere('posturas_idposturas','=',$idpostura_contraparte)->where('postura_contraparte_id','=',$idpostura)->orWhere('postura_contraparte_id','=',$idpostura_contraparte)->get();
                $this->code = "OK";
                
                if(count($posturasMatch) > 0)
                {
                    $this->data = $posturasMatch;
                }
                else
                {
                    $this->message = "No hay resultados";
                }
            }
            else
            {
                $this->message = "Los parametros requeridos deben ser números mayores a 0 (cero).";
            }
        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        return response()->json([
            'code'      => $this->code,
            'message'   => $this->message,
            'data'      => $this->data
        ],200);
    }

    public function indicadores_de_mercado()
    {
        $this->code = "OK";
        $op_concretada = 3;
        $op_activa = 1;
        $op_bs = 1;
        $op_usd = 2;
        $ultima_operacion = array();
        $indicadores = array();
        $current_date = (new DateTime(''))->format('Y-m-d H:i:s');
        $tasa_cambio_del_dia_monto = 0;

        $posturas_concretadas = DB::table('posturas_matches as pos_mat')->join('posturas as pos', 'pos.idposturas', '=', 'pos_mat.posturas_idposturas')->where('estatusOperaciones_idestatusOperacion','=',$op_concretada)->orderBy('idposturasMatch', 'desc')->get();

        $posturas_activas = DB::table('posturas')->where('estatusPosturas_idestatusPosturas','=',$op_activa)->get();

        if( ($posturas_concretadas != null) && (count($posturas_concretadas) > 0 ))
        {
            // Se considerará es el último movimiento cuya operación tiene status “concretada”
            $ultima_operacion = $posturas_concretadas->first();
            
            $postura_origen = DB::table('posturas')->where('idposturas','=',$ultima_operacion->posturas_idposturas)->get();

            // Aparecerá la menor tasa de cambio que se ejecutó en una operación con status “concretada” en el día
            $menor_tasa_del_dia = $this->get_tasa_cambio(1,3);

            // Aparecerá la mayor tasa de cambio que se ejecutó en una operación con status “concretada” en el día
            $mayor_tasa_del_dia = $this->get_tasa_cambio(1,4); 
        }

        //  Promedio de todas las transacciones del dia anterior o ultimo dia con transacciones en el sistema
        $tasa_cambio_del_dia = $this->get_tasa_cambio(1,1);
        
        // Ultima transaccion cerrada en el sistema
        $tasa_apertura = $this->get_tasa_cambio(1,2);

        if( count($posturas_activas) > 0)
        {
            // Aparecerá la cantidad de posturas en donde en el campo “quiero” colocaron BsF
            $cantidad_ofertas_abiertas = $posturas_activas->where('quiero_moneda_id','=',$op_bs)->count();

            // Aparecerá la cantidad de posturas en donde en el campo “quiero” colocaron USD
            $cantidad_demandas_abiertas = $posturas_activas->where('quiero_moneda_id','=',$op_usd)->count();
        }
        else
        {
            $cantidad_ofertas_abiertas = 0;
            $cantidad_demandas_abiertas = 0;
        }

        if( ($posturas_concretadas != null) && (count($posturas_concretadas) > 0 ))
        {
            $indicadores = array(
                'tasa'=>
                    array(
                        'fecha'     => $current_date,
                        //$ultima_operacion->updated_at,
                        'tasacambio'=> $tasa_cambio_del_dia
                    ),
                'mejor_compra_del_dia' => $menor_tasa_del_dia,
                'mejor_venta_del_dia' => $mayor_tasa_del_dia,
                'tasa_apertura_del_dia' => $tasa_apertura,
                'cantidad_ofertas_abiertas' => $cantidad_ofertas_abiertas,
                'cantidad_demandas_abiertas' => $cantidad_demandas_abiertas
            );
        }
        else
        {
            $indicadores = array(
                'ultimo_movimiento'=>
                    array(
                        'fecha'     => (new DateTime(''))->format('Y-m-d H:i:s'),
                        // 'fecha'     => date('d/m/Y', strtotime($ultima_operacion->updated_at)),
                        // 'hora'      => date('H:i:s', strtotime($ultima_operacion->updated_at)),
                        'tasacambio'=> 0
                    ),
                'mejor_compra_del_dia' => 0,
                'mejor_venta_del_dia' => 0,
                'tasa_cambio_del_dia' => 0,
                'cantidad_ofertas_abiertas' => $cantidad_ofertas_abiertas,
                'cantidad_demandas_abiertas' => $cantidad_demandas_abiertas
            );
        }

        return response()->json([
            'code'      => $this->code,
            'message'   => $this->message,
            'data'      => $indicadores
        ],200);
    }

    private function get_tasa_cambio($restar_dia,$opcion)
    {
        $cantidad_dias = 30;
        $op_concretada = 3;
        $tasa_cambio_del_dia = array();
        $cont = 1;

        while($cont <= $cantidad_dias)
        {
            $tasa_cambio_del_dia = DB::table('posturas_matches as pos_mat')->join('posturas as pos', 'pos.idposturas', '=', 'pos_mat.posturas_idposturas')->select('pos.tasacambio')->where('estatusOperaciones_idestatusOperacion','=',$op_concretada)->whereDay('pos_mat.created_at', '=', (date('d')-$restar_dia))->whereMonth('pos_mat.created_at', '=', date('m'))->whereYear('pos_mat.created_at', '=', date('Y'))->get();

            $restar_dia += 1;
            $cont += 1;

            if($tasa_cambio_del_dia->first() != null)
            {
                $cont = 31;
            }

        }

        if($tasa_cambio_del_dia->first() != null)
        {
            switch ($opcion)
            {
                case 1: // Promedio tasa de cambio
                    $tasa_cambio_del_dia_monto = self::promediar_tasa_cambio($tasa_cambio_del_dia);
                break;
                case 2: // Tasa apertura del dia
                    $tasa_cambio_del_dia_monto = $tasa_cambio_del_dia->first()->tasacambio;
                break;
                case 3: // Menor tasa del dia
                    $tasa_cambio_del_dia_monto = $tasa_cambio_del_dia->min('tasacambio');
                break;
                case 4: // Mayor tasa del dia
                    $tasa_cambio_del_dia_monto =$tasa_cambio_del_dia->max('tasacambio');
                break;
            }
        }
        else
        {
            $tasa_cambio_del_dia_monto = 0;
        }

        return $tasa_cambio_del_dia_monto;
    }

    private function promediar_tasa_cambio($array_tasas_cambio)
    {
        $monto = 0;
        $cantidad = count($array_tasas_cambio);

        foreach ($array_tasas_cambio as $tasa)
        {
            $monto += (int) $tasa->tasacambio;
        }

        $monto = $monto/$cantidad;
        return $monto;
    }

    private function save_log_user($user_id,$postura_match_id,$comment)
    {
        error_log("..................save_log_user.................. ");
        
        $log_user = new \App\log_user();
        $log_user->accion = $comment;
        $log_user->user_id = $user_id;
        $log_user->posturas_match_id = $postura_match_id;
        $log_user->save();
    }

    public function log_por_usuario_y_postura($user_id, $postura_match_id)
    {
        $log_data = [];

        if( is_numeric($user_id) && is_numeric($postura_match_id))
        {

            if($user_id != 0 && $postura_match_id != 0)
                $log_data = DB::table('log_users as log_u')->join('users as u', 'u.id', '=', 'log_u.user_id')->select('log_u.id','log_u.accion','u.id as user_id','u.username','log_u.created_at')->where('log_u.user_id','=',$user_id)->where('log_u.posturas_match_id','=',$postura_match_id)->orderBy('id', 'desc')->get();
            else if($user_id != 0 && $postura_match_id == 0)
                $log_data = DB::table('log_users as log_u')->join('users as u', 'u.id', '=', 'log_u.user_id')->select('log_u.id','log_u.accion','u.id as user_id','u.username','log_u.created_at')->where('log_u.user_id','=',$user_id)->orderBy('id', 'desc')->get();
            else if($user_id == 0 && $postura_match_id != 0)
                $log_data = DB::table('log_users as log_u')->join('users as u', 'u.id', '=', 'log_u.user_id')->select('log_u.id','log_u.accion','u.id as user_id','u.username','log_u.created_at')->where('log_u.posturas_match_id','=',$postura_match_id)->orderBy('id', 'desc')->get();
        }
        return $log_data;
    }
}
