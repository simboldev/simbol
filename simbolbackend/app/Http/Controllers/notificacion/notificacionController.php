<?php

namespace App\Http\Controllers\notificacion;

use DB;
use App\notificacion;
use Illuminate\Http\Request;
use App\notificaciones_has_users;
use App\Http\Controllers\Controller;

class notificacionController extends Controller
{
  var $charset="UTF-8";
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

        $notificacion = new \App\notificacion($request->all());
        
        if( $notificacion->save())
        {
            $data       = $notificacion;
        }
        else
        {
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar guardar notificación";         
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);
    }


    public function savePostAndNotif($users_id,$idnotif){
        $code       = "OK";
        $message    = "";
        $data       = [];

        $notifUser = new \App\notificaciones_has_users();
        $notifUser->users_id = $users_id;
        $notifUser->notificaciones_idnotificaciones = $idnotif;

        if($notifUser->save()){
            $data = $notifUser;
        }
        else
        {
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar guardar notificación";         
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }

    /*Metodo para consultar la tabla pivot notificaciones_has_users para traer
    las notificaciones*/
    public function consNotXuser($iduser,$v_p){
        $code       = "OK";
        $message    = "";
        $data       = [];

        if(is_numeric($iduser)){

            $nhu = DB::table('notificacions')
                ->join('notificaciones_has_users','notificacions.idnotificaciones','=','notificaciones_has_users.notificaciones_idnotificaciones')
                ->where('notificacions.notLeida','=',0)
                ->where('notificaciones_has_users.users_id','=',$iduser)  
                ->select('notificaciones_has_users.notificaciones_idnotificaciones as idnot')      
                ->get();
           $data =  $nhu;

        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
            $data = 0;
        }
        
        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }
    
    //Metodo para consultar notificaciones por usuario que no esten leidas y por orden de fecha desde la mas reciente a la m�s antigua
    public function consNotUserDate($iduser){
        error_log(" -------------------- consNotUserDate");
        $code       = "OK";
        $message    = "";
        $data       = [];
        error_log('$charset ================== '.$this->charset);
        if(is_numeric($iduser)){
            
            $not = DB::table('notificacions')
                      ->join('notificaciones_has_users','notificacions.idnotificaciones','=','notificaciones_has_users.notificaciones_idnotificaciones')
                      //->where('notificacions.notLeida','=',0)
                      ->where('notificaciones_has_users.users_id','=',$iduser)
                      ->orderBy('notificaciones_has_users.created_at', 'desc')
                      ->select(
                              'notificacions.idnotificaciones',
                              'notificacions.titulo',
                              'notificacions.cuerpo',
                              'notificacions.notLeida',
                              'notificacions.status_notifications_id',
                              'notificacions.created_at',
                              'notificaciones_has_users.postura_match_id')
                      //->limit(100)
                      ->get();

            error_log($not);
            $j=1;
            for($i = 0 ;$i <= count($not)-1;$i++){
                error_log(count($not));
                $str_titulo = iconv($this->charset, 'ASCII//TRANSLIT', $not[$i]->titulo);
                // $resultadoTracking = strpos($str_titulo,'Tracking');
                // $resultadoConfiabilidad = strpos($str_titulo,'Confiabilidad');
                // $resultadoNegociacion = strpos($str_titulo,'Negociacion');
                
                $resultadoTracking = ($str_titulo == 'Tracking') ? true : false ;
                $resultadoConfiabilidad = ($str_titulo == 'Confiabilidad') ? true : false ;
                $resultadoNegociacion = ($str_titulo == 'Negociacion') ? true : false ;

                
                $idPostMatch =  $not[$i]->postura_match_id;  
                
                if($resultadoTracking == true){
                  error_log('$resultadoTracking ================== '.$resultadoTracking);
                    if($not[$i]->postura_match_id){
                          if($idPostMatch){
                            $not[$i]->identificador=1;
                            $not[$i]->idPostMatch=$idPostMatch; 
                          }else{
                            $not[$i]->identificador=2;
                          }           
                    }else{
                        $not[$i]->identificador=1;
                    }   
                }
                
                if($resultadoConfiabilidad == true){
                  error_log('$resultadoConfiabilidad ================== '.$resultadoConfiabilidad);
                    if($not[$i]->postura_match_id){
                        $idPostMatch =  $not[$i]->postura_match_id;         
                          if($idPostMatch){
                            $not[$i]->identificador=5;
                            $not[$i]->idPostMatch=$idPostMatch; 
                          }else{
                            $not[$i]->identificador=2;
                          }   
                    }else{
                      $not[$i]->identificador=5;
                    }
                    
                }

                if($resultadoNegociacion == true){
                  error_log('$resultadoNegociacion ================== '.$resultadoNegociacion);
                    if($not[$i]->postura_match_id){
                        $idPostMatch =  $not[$i]->postura_match_id;         
                        if($idPostMatch){
                          $not[$i]->identificador=6;
                          $not[$i]->idPostMatch=$idPostMatch; 
                        }else{
                          $not[$i]->identificador=2;
                        }   
                    }else{
                      $not[$i]->identificador=6;
                    }
                    
                }

                if($not[$i]->status_notifications_id == 1 && ($resultadoTracking == false && $resultadoConfiabilidad == false && $resultadoNegociacion == false))
                {
                  error_log('$if grande ================== ');
                  // Aplica cuando las notificaciones son MATCH que se le muestran a la contraparte.
                  $idPostMatch= DB::table('posturas_matches')
                                    ->where('idposturasMatch','=',$not[$i]->postura_match_id)
                                    ->where('iduser2','=',$iduser)
                                    ->get();
                  error_log($idPostMatch);
                    if($idPostMatch){
                        if($not[$i]->notLeida == 0)
                        {
                          // Si la notificacion no ha sido leida, codico para enviar al detalle de la postura
                          $not[$i]->identificador=3;
                        }
                        else
                        {
                          // Si la notificacion ha sido leida, codigo para enviar directo al chat
                          $not[$i]->identificador=4;

                        }

                        $not[$i]->data=$idPostMatch;
                        error_log(" --------- identificador=3---------------");
                        error_log($not[$i]->data);

                    }
                }
                else if((int)$not[$i]->status_notifications_id == 2 && ($resultadoTracking == false && $resultadoConfiabilidad == false && $resultadoNegociacion == false))
                {
                  // Aplica cuando las notificaciones son MATCH que se le muestran al propietario.

                  $idPostMatch= DB::table('posturas_matches')
                                    ->where('idposturasMatch','=',$not[$i]->postura_match_id)
                                    ->where('users_idusers','=',$iduser)
                                    ->get();
                  error_log($idPostMatch);


                    if($idPostMatch){
                        $not[$i]->identificador=4;
                        $not[$i]->data=$idPostMatch;
                        error_log(" --------- identificador=4---------------");

                    }
                }
                /*else
                {
                    $not[$i]->identificador=0;
                } */ 

                $not[$i]->num =$j++;
            }  

            $data = $not;

        }else{
            $code = "NOTOK";
            $message = "Par�metro requerido (id) debe ser un valor num�rico";
            $data = 0;
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
        //para ajustar en un futuro

        $code       = "OK";
        $message    = "";
        $data       = [];

        $fecha = date("Y-m-d");

        if(is_numeric($id)){
            $not = notificacion::select(
                                'idnotificaciones',
                                'titulo',
                                'cuerpo')
                                ->where('idnotificaciones','=',$id)
                                ->where('NotLeida','=',0)
                                ->where('created_at','like','%'.$fecha.'%')
                                ->get();

            if(count($not) == 0){
                $message = "La consulta no trajo valores";
            }                    
        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        $data = $not;
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

    //metodo para actualizar todas las notificaciones de un usuario
    public function update_all_not($id){

         $code       = "OK";
         $message    = "";
         $data       = [];

         $query = DB::table('notificacions')
                ->join('notificaciones_has_users','notificacions.idnotificaciones','=','notificaciones_has_users.notificaciones_idnotificaciones')
                ->where('notificaciones_has_users.users_id','=',$id)
                ->update(['notificacions.notleida' => 1]);

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
          ],200);   

    }

    //Método para modificar el estatus de una notificación a leida
    public function modEstatusNot($idnot){
        
         $code       = "OK";
         $message    = "";
         $data       = [];
         
         $query = DB::table('notificacions')
                ->where('idnotificaciones', $idnot)
                ->update(['notleida' => 1]);

        switch ($query)
        {
                case 0:
                    $code = "OK";
                    $message = "La notificacion ya se encuentra en el estatus solicitado.";
                    $data = 0;
                    break;
                case 1:
                    $code = "OK";
                    $message = 1;
                    $data = $query;
                    break;
                default:
                    $code       = "NOTOK";
                    $message    = "Error al intentar actualizar la data, intente nuevamente.";
                    $data = 3;
                    break;
        }
        
        return response()->json([
            'code'      => $code,
            'msg'   => $message,
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
}
