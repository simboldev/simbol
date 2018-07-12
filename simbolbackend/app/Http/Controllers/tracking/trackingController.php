<?php

namespace App\Http\Controllers\tracking;

use DB;
use App\User;
use App\tracking;
use App\notificacion;
use App\posturasMatches;
use Illuminate\Http\Request;
use App\notificaciones_has_users;
use App\Http\Controllers\Controller;




class trackingController extends Controller
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
      error_log("===============CREANDO CHAT PRIMERA FASE========================");
        //$requestt = file_get_contents('php://input');
        //$dat = json_decode($requestt);
       
        $code       = "OK";
        $message    = "";
        $data       = [];

        $tracking = new \App\tracking();
        $tracking->transferi = $request->transferi;
        $tracking->metransfirieron = $request->metransfirieron;
        $tracking->conformetransfiere = $request->conformetransfiere;
        $tracking->conformetransferido = $request->conformetransferido;
        $tracking->id = $request->idp;
        $tracking->iduser = $request->iduser;
        $tracking->iduser2 = $request->iduser2;
        $tracking->opsatisf1 = $request->opsatisf1;
        $tracking->opsatisf2 = $request->opsatisf2;
        
        if( $tracking->save())
        {

            $data   =   $tracking;
             
            $respPostMatch = $this->savePosturasMatch($data->id,$request->idp);
            if($respPostMatch == true){
                $respNotif=$this->saveNotify($data->iduser,$data->iduser2,$request->idp);
                if($respNotif == true){
                    $resp=true;
                }else{
                    $code       = "NOTOK";
                    $message    = "Ocurrio un problema al intentar guardar notificación";
                }
            }else{
                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar guardar idtracking en posturasMatch";
            }

        }
        else
        {
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar guardar tracking";         
        }
      
        /*if($resp == true){
            $data = $dat;
        }*/

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);
    }

    /*Método para actualizar la tabla posturas match posterior 
    a la inserción del tracking*/
    public function savePosturasMatch($idtracking,$idposturasMatch){
        //$posturasMatch = new \App\posturasMatch();
        if(\App\posturasMatches::where('idposturasMatch',$idposturasMatch)
                ->update(['trackings_idtracking'=>$idtracking])){
            return true;
        }else{
            return false;
        }

    }

    //Método para guardar en la tabla notificaciones
    public function saveNotify($idusuarioC,$iduser,$idPostMatch){
      
        $usuario = User::select('username')->where('id','=',$idusuarioC)->get();
        
        $notificacion = new \App\notificacion();
        $not_has_user = new \App\notificaciones_has_users();

        $notificacion->titulo='Notificación de Tracking';
        $notificacion->cuerpo='Estimado usuario se le notifica que el usuario '.$usuario[0]->username.' ha checkeado el tracking de la operación';
        $notificacion->adjunto='';
        $notificacion->notleida=0;
        $notificacion->status_notifications_id =1;

        if($notificacion->save()){
            $not_has_user->users_id = $iduser;
            $not_has_user->notificaciones_idnotificaciones=$notificacion->id;
            $not_has_user->postura_match_id=$idPostMatch;
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
        $code       = "OK";
        $message    = "";
        $data       = [];
        //$tracking = tracking::select('transferi','metransfirieron','conformetransfiere','conformetransferido')->where('id','=',$id)->get();
        if( is_numeric($id))
        {
            $data = tracking::find($id);
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
    public function update(Request $request, tracking $tracking)
    {
     
        
    }
    
    
    public function mod($idp,$fase){
        error_log("===============Tracking mod========================");
        $code       = "OK";
        $message    = "";
        $data       = [];
        
        $tracking = tracking::select('transferi','metransfirieron','conformetransfiere','conformetransferido','id','iduser','iduser2','opsatisf1','opsatisf2')
                    ->where('id','=',$idp)
                    ->get();
        $data       = $tracking;

        if($tracking){
              if($fase == 1){
                  if(\App\tracking::where('id',$data[0]->id)
                      ->update(['conformetransfiere'=>1,'opsatisf2'=>1])){
                      $code       = "OK";
                      $message    = "Success";
                      $respNotif=$this->saveNotify($data[0]->iduser2,$data[0]->iduser,$idp);
                  }else{
                      $code       = "NOTOK";
                      $message    = "Ocurrio un problema al intentar actualizar la informació de tracking";
                  }
              }else if($fase == 2){
                  if(\App\tracking::where('id',$data[0]->id)
                      ->update(['metransfirieron'=>1])){
                      $code       = "OK";
                      $message    = "Success";
                      $respNotif=$this->saveNotify($data[0]->iduser2,$data[0]->iduser,$idp);
                  }else{
                      $code       = "NOTOK";
                      $message    = "Ocurrio un problema al intentar actualizar la informació de tracking";
                  }
              }else if($fase == 3){
                  if(\App\tracking::where('id',$data[0]->id)
                      ->update(['conformetransferido'=>1])){
                      $code       = "OK";
                      $message    = "Success";
                      $respNotif=$this->saveNotify($data[0]->iduser,$data[0]->iduser2,$idp);
                  }else{
                      $code       = "NOTOK";
                      $message    = "Ocurrio un problema al intentar actualizar la informació de tracking";
                  }   
              }   
          }else{
              $code       = "NOTOK";
              $message    = "Ocurrio un problema al intentar actualizar la informació de tracking";
          }
          
          if($respNotif == true){
                  $resp=true;
          }else{
                  $code       = "NOTOK";
                  $message    = "Ocurrio un problema al intentar guardar notificación";
          } 
        
        
          return response()->json([
              'code'      => $code,
              'msg'   => $message,
              'data'      => $data
          ],200);
                
    }
    
    //modificar estatus del boton operacion satisfactoria
    function modEstatusBoton($idPm,$idUser){
        $code       = "OK";
        $message    = "";
        $data       = [];

        $tracking = tracking::select('transferi','metransfirieron','conformetransfiere','conformetransferido','id','iduser','iduser2','opsatisf1','opsatisf2')
                    ->where('id','=',$idPm)
                    ->get();

        if($tracking){
            if($tracking[0]->iduser == $idUser){

                \App\tracking::where('iduser',$idUser)
                ->where('id',$idPm)
                ->update(['opsatisf1'=>1]);
                $code       = "OK";
                $message    = "Success";

            }else if($tracking[0]->iduser2 == $idUser){
                \App\tracking::where('iduser2',$idUser)
                ->where('id',$idPm)
                ->update(['opsatisf2'=>1]);
                $code       = "OK";
                $message    = "Success";

            }

            $tracking = tracking::select('transferi','metransfirieron','conformetransfiere','conformetransferido','id','iduser','iduser2','opsatisf1','opsatisf2')
                    ->where('id','=',$idPm)
                    ->get();

            $data = $tracking;
            
        }else{
              $code       = "NOTOK";
              $message    = "Ocurrio un problema al intentar actualizar la informació de tracking";
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
