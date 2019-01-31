<?php

namespace App\Http\Controllers\negociacion;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\negociacion;
use App\estatusNegociacion;
use App\postura;
use App\posturasMatches;
use App\User;
use App\notificacion;
use App\notificaciones_has_users;
use Storage;

class negociacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        error_log("===============index NEGOCIACION========================");
        $code       = "OK";
        $message    = "";
        $data       = [];

        // $dataNegociacion = posturasMatches::select(
        //     'posturas_matches.idposturasMatch as idneg',
        //     DB::raw("IF(posturas_matches.estatusOperaciones_idestatusOperacion = '4','procesado','en proceso') as estatus"),
        //     'posturas_matches.created_at as fecha',
        //     'posturas.tasacambio as tasa',
        //     'posturas.quiero as divisa1',
        //     'posturas.tengo as divisa2'
        // )
        // ->join('posturas','posturas.idposturas','posturas_matches.posturas_idposturas')
        // ->join('negociacions','negociacions.idposturamatch','posturas_matches.idposturasMatch')
        // ->whereIn('negociacions.estatusnegociacion',[2,3])
        // ->whereNotIn('posturas_matches.estatusOperaciones_idestatusOperacion',[0])
        // ->orderBy('posturas_matches.idposturasMatch','DESC')
        // ->get();

        
      $estatusNegociaciones = estatusNegociacion::all();

      $query_neg = negociacion::select(
          'negociacions.id',
          'negociacions.iduser as id_usuario',
          'users.username as usuario_nombre_usuario',
          'negociacions.estatusnegociacion as id_estatus_negociacion',
          'estatus_negociacions.estatus as estatus_negociacion',
          'negociacions.estatus_autoriza_backoffice',
          'posturas_matches.idposturasMatch as id_postura_match',
          'posturas_matches.created_at as fecha'
          // ,
          // 'posturas.tasacambio as tasa_cambio',
          // 'posturas.quiero_moneda_id',
          // 'posturas.quiero',
          // 'posturas.tengo_moneda_id',
          // 'posturas.tengo'
        )
        ->join('users','users.id','negociacions.iduser')
        ->join('estatus_negociacions','estatus_negociacions.id','negociacions.estatusnegociacion')
        ->join('posturas_matches','posturas_matches.idposturasMatch','negociacions.idposturamatch')
        // ->join('posturas','posturas.idposturas','posturas_matches.posturas_idposturas')
        ->whereIn('negociacions.estatusnegociacion',[2,3])
        ->orderBy('negociacions.id','DESC')
        ->get();

      foreach ($query_neg as $negociacion)
      {
        $postura_match = posturasMatches::find($negociacion->id_postura_match);

        if($postura_match->users_idusers == $negociacion->id_usuario)
        {
            $postura = postura::where('idposturas',$postura_match->posturas_idposturas)->first();
        }
        else if($postura_match->iduser2 == $negociacion->id_usuario)
        {
            $postura = postura::where('idposturas',$postura_match->postura_contraparte_id)->first();
        }

        $negociacion['tasacambio'] = $postura->tasacambio;
        $negociacion['quiero_moneda_id'] = $postura->quiero_moneda_id;
        $negociacion['quiero'] = $postura->quiero;
        $negociacion['tengo_moneda_id'] = $postura->tengo_moneda_id;
        $negociacion['tengo'] = $postura->tengo;

        if($negociacion->quiero_moneda_id == 2 && $negociacion->id_estatus_negociacion)
          $negociacion->estatus_negociacion = estatusNegociacion::where('id',1)->first()->estatus;
      }

      $data = $query_neg;

      return response()->json([
          'code'      => $code,
          'msg'       => $message,
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
        $data       = array();

        return response()->json([
            'code'  => $code,
            'msg'   => $message,
            'data'  => $data
        ],200);

    }
    
    public function negociacion_por_postura_match($idPosturaMatch)
    {
        error_log('=================negociacion_por_postura_match ========================'.$idPosturaMatch);
        $code       = "OK";
        $message    = "";
        $data       = array();

        $negociaciones = $this->negociacion_por_postura_y_usuario($idPosturaMatch, null,null);
 
        foreach ($negociaciones as $negociacion)
        {
          $format_negociacion = $this->arreglo_negociacion($idPosturaMatch, $negociacion);
          if($format_negociacion['quiero_moneda'] == 1)
            $data['negociacion_bs'] = $format_negociacion;
          else if($format_negociacion['quiero_moneda']  == 2)
            $data['negociacion_moneda_extranjera'] = $format_negociacion;
        }

        return response()->json([
            'code'  => $code,
            'msg'   => $message,
            'data'  => $data
        ],200);
    }

    public function consultNeg($idPosturaMatch,$iduser)
    {
        error_log("===============consultNeg NEGOCIACION======== idPosturaMatch = ".$idPosturaMatch.' $iduser = '.$iduser);
        $code       = "OK";
        $message    = "";
        $data       = [];
        $datUserMon = '';

        $existNeg = $this->negociacion_por_postura_y_usuario($idPosturaMatch, $iduser,'=')->first();
        // AGG aca datos de la neg. de la contraparte

        if($existNeg == null)
        {
            $data = [
                      'mi_negociacion'=>[ 'estatusNeg'=>0,
                                          'estatus_autoriza_backoffice'=>0,
                                          'iduser'=> (int) $iduser,
                                          'quiero_moneda'=>0],
                      'negociacion_contraparte' => []

            ];
        }
        else if($existNeg != null)
        {
            $negContraparte = $this->negociacion_por_postura_y_usuario($idPosturaMatch, $iduser,'!=')->first();

            $data = [ 
                      'mi_negociacion'=> $this->arreglo_negociacion($idPosturaMatch, $existNeg),
                      'negociacion_contraparte' => ($negContraparte != null) ? $this->arreglo_negociacion($idPosturaMatch, $negContraparte) : []
            ];
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

    private function negociacion_por_postura_y_usuario($idPosturaMatch, $iduser, $condicion_usuario)
    {
      error_log('===========AAAnegociacion_por_postura_y_usuario============== idPosturaMatch = '.$idPosturaMatch.' $iduser = '.$iduser.'  $condicion_usuario = '.$condicion_usuario);
      $user = User::where('id',$iduser)->first();
      $query_neg = negociacion::select(
                        'negociacions.id',
                        'negociacions.estatusnegociacion',
                        'negociacions.estatus_autoriza_backoffice',
                        'negociacions.iduser',
                        'bancos.nombre as banco',
                        'negociacions.aba',
                        'negociacions.nrocuenta',
                        'negociacions.email',
                        'negociacions.nroidentificacion',
                        'negociacions.comprobante'
        )
        ->join('bancos','bancos.idbancos','negociacions.idbanco');
        error_log($idPosturaMatch != null);
        if($idPosturaMatch != null)
          $query_neg->where('idposturamatch',$idPosturaMatch);
        error_log($iduser != null && $condicion_usuario != null && $user->tipousuario_idtipousuario != 5);
        if($iduser != null && $condicion_usuario != null && $user->tipousuario_idtipousuario != 5)
          $query_neg->where('iduser',$condicion_usuario,$iduser);
        
        $existNeg = $query_neg->get();

        if( $existNeg != null)
        {
          foreach ($existNeg as $negociacion)
          {
            error_log('foeee '.$negociacion->id);
            error_log($negociacion->comprobante != ''); 
            if($negociacion->comprobante != '')
              // $negociacion->comprobante = Storage::path($existNeg->comprobante);
              // $existNeg->comprobante = asset(Storage::url($existNeg->comprobante));
            error_log('sassssssssssssssssssss');
            error_log($negociacion->estatus_autoriza_backoffice);
            if($negociacion->estatus_autoriza_backoffice == null || $negociacion->estatus_autoriza_backoffice == '')
              $negociacion->estatus_autoriza_backoffice = 0;
          }
        }
        return $existNeg;
    }

    private function quiero_moneda($idPosturaMatch, $iduser)
    {
      $postura_match = posturasMatches::find($idPosturaMatch);

      if($postura_match->users_idusers == $iduser)
      {
          $datUserMon = postura::where('idposturas',$postura_match->posturas_idposturas)->first()->quiero_moneda_id;
      }
      else if($postura_match->iduser2 == $iduser)
      {
          $datUserMon = postura::where('idposturas',$postura_match->postura_contraparte_id)->first()->quiero_moneda_id;
      }

      return $datUserMon;
    }

    private function arreglo_negociacion($idPosturaMatch, $negociacion)
    {

      $arr_negociacon = [ 'idNeg' => $negociacion->id,
                          'estatusNeg'=> $negociacion->estatusnegociacion,
                          'estatus_autoriza_backoffice'=>$negociacion->estatus_autoriza_backoffice,
                          'iduser'=> $negociacion->iduser,
                          'usuario_nombre_usuario'=>User::where('id',$negociacion->iduser)->first()->username,
                          'quiero_moneda'=> $this->quiero_moneda($idPosturaMatch, $negociacion->iduser),
                          'aba' => ($negociacion->aba != 'undefined' )? $negociacion->aba : '',
                          'banco' => $negociacion->banco,
                          'nrocuenta' => $negociacion->nrocuenta,
                          'email' =>  $negociacion->email,
                          'nroidentificacion' => $negociacion->nroidentificacion,
                          'comprobante'   => ($negociacion->comprobante != null) ? join('',explode ("/public", ENV('BASE_URL_COMPROBANTES').$negociacion->comprobante)) : ''
                        ];
                        
      return $arr_negociacon;
    }

    public function saveNegociacion($idbancoNeg,$abaNeg,$nrocuentaNeg,$emailNeg,$nacionalidadNeg,$nroidentificacionNeg,$idposturamatchNeg,$iduser,$iduser_contraparte)
    {
        error_log("===============saveNegociacion NEGOCIACION========");
        error_log('$iduser = '.$iduser.', $iduser_contraparte ='.$iduser_contraparte);
        $code       = "OK";
        $message    = "";
        $data       = [];

        $identificacion = $nacionalidadNeg.$nroidentificacionNeg;

        $existNeg = negociacion::select('estatusnegociacion')
            ->where('idposturamatch',$idposturamatchNeg)
            ->get();

        $negociacion = new \App\negociacion();
        $negociacion->idbanco = $idbancoNeg;
        $negociacion->aba = $abaNeg;
        $negociacion->nrocuenta = $nrocuentaNeg;
        $negociacion->email = $emailNeg;
        $negociacion->nroidentificacion = $identificacion;
        $negociacion->idposturamatch = $idposturamatchNeg;
        $negociacion->estatusnegociacion = 1;
        $negociacion->iduser = $iduser;

        if($negociacion->save())
        {
          $data = ['estatusNeg'=>1];
          $negociaciones = negociacion::where('iduser','!=', $negociacion->iduser)->where('estatusnegociacion',$negociacion->estatusnegociacion)->where('idposturamatch',$negociacion->idposturamatch)->get();

          $postura_match = posturasMatches::find($negociacion->idposturamatch);
          error_log('ssssssss postura_match = ');
          error_log($postura_match);
          error_log($postura_match->users_idusers);
          error_log($negociacion->iduser);
          if($postura_match->users_idusers == $negociacion->iduser)
          {
            error_log('ifff');
              $postura = postura::where('idposturas',$postura_match->posturas_idposturas)->first();
          }
          else if($postura_match->iduser2 == $negociacion->iduser)
          {
            error_log('elseee');
              $postura = postura::where('idposturas',$postura_match->postura_contraparte_id)->first();
          }
          error_log($postura);
          
          $user_emisor = User::find($iduser);
          $user_contraparte = User::find($iduser_contraparte);
          $tittle_noti = 'Negociación';
          
          if($postura->quiero_moneda_id == 1) // Bs
          {

            $estatus_negociacion = 'agregó los datos bancarios.';

            if(count($negociaciones) > 0)
            {
              $estatus_negociacion = $estatus_negociacion.' Ya le puedes transferir.';
            }
            else
            {
              $estatus_negociacion = $estatus_negociacion.' Agrega tus datos para que puedas transferirle.';
            }
          }
          else if($postura->quiero_moneda_id == 2) // USD
          {
            if(count($negociaciones) > 0)
            {
              $estatus_negociacion = 'está relizando la transferencia.';
            }
            else
            {
              $estatus_negociacion = 'espera que agregues tus datos bancarios para transferirte.';
            }
          }

          $text_noti = strtoupper($user_emisor->username).' '.$estatus_negociacion;
          $this->save_notification($user_contraparte->id,$tittle_noti, $text_noti, $negociacion->idposturamatch,1);
          $message = "La negociación se ha guardado con exito";
        }else{
          $message = "La negociación no se ha podido guardar con exito";
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }

    // public function saveNegotiation(Request $request)
    // {
    //     error_log("===============saveNegociacion NEGOCIACION========");
    //     error_log('$request = '.$request);

    //     $code       = "OKlklk";
    //     $message    = "";
    //     $data       = [];

    //     $identificacion = $request->nacionalidadNeg.$request->nroidentificacionNeg;

    //     $existNeg = negociacion::select('estatusnegociacion')
    //         ->where('idposturamatch',$request->idposturamatchNeg)
    //         ->get();
    //     error_log($request->idbancoNeg);
    //     // error_log($request['abaNeg']);
    //     // error_log($request['nrocuentaNeg']);
    //     // error_log($request['emailNeg']);
    //     // error_log($request['idposturamatchNeg']);
    //     // error_log($request['idUser']);
    //     // error_log($request['iduser_contraparte']);

    //     error_log($request['idbancoNeg']);
    //     // error_log($request['abaNeg']);
    //     error_log($request['nrocuentaNeg']);
    //     error_log($request['emailNeg']);
    //     error_log($request['idposturamatchNeg']);
    //     error_log($request['idUser']);
    //     error_log($request['iduser_contraparte']);

    //     $negociacion = new \App\negociacion();
    //     $negociacion->idbanco = $request['idbancoNeg'];
    //     $negociacion->aba = $request['abaNeg'];
    //     $negociacion->nrocuenta = $request['nrocuentaNeg'];
    //     $negociacion->email = $request['emailNeg'];
    //     $negociacion->nroidentificacion = $identificacion;
    //     $negociacion->idposturamatch = $request['idposturamatchNeg'];
    //     $negociacion->estatusnegociacion = 1;
    //     $negociacion->iduser = $request['idUser'];
    //     error_log('$negociacion = '.$negociacion);
    //     if($negociacion->save())
    //     {
    //       $data = ['estatusNeg'=>1];
    //       $negociaciones = negociacion::where('iduser','!=', $negociacion->iduser)
    //                       ->where('estatusnegociacion',$negociacion->estatusnegociacion)
    //                       ->where('idposturamatch',$negociacion->idposturamatch)
    //                       ->get();

    //       $postura_match = posturasMatches::find($negociacion->idposturamatch);
    //       error_log('ssssssss postura_match = ');
    //       error_log($postura_match);
    //       error_log($postura_match->users_idusers);
    //       error_log($negociacion->iduser);
    //       if($postura_match->users_idusers == $negociacion->iduser)
    //       {
    //         error_log('ifff');
    //           $postura = postura::where('idposturas',$postura_match->posturas_idposturas)->first();
    //       }
    //       else if($postura_match->iduser2 == $negociacion->iduser)
    //       {
    //         error_log('elseee');
    //           $postura = postura::where('idposturas',$postura_match->postura_contraparte_id)->first();
    //       }
    //       error_log($postura);
          
    //       $user_emisor = User::find($request['idUser']);
    //       $user_contraparte = User::find($request['iduser_contraparte']);
    //       $tittle_noti = 'Negociación';
          
    //       if($postura->quiero_moneda_id == 1) // Bs
    //       {

    //         $estatus_negociacion = 'agregó los datos bancarios.';

    //         if(count($negociaciones) > 0)
    //         {
    //           $estatus_negociacion = $estatus_negociacion.' Ya le puedes transferir.';
    //         }
    //         else
    //         {
    //           $estatus_negociacion = $estatus_negociacion.' Agrega tus datos para que puedas transferirle.';
    //         }
    //       }
    //       else if($postura->quiero_moneda_id == 2) // USD
    //       {
    //         if(count($negociaciones) > 0)
    //         {
    //           $estatus_negociacion = 'está relizando la transferencia.';
    //         }
    //         else
    //         {
    //           $estatus_negociacion = 'espera que agregues tus datos bancarios para transferirte.';
    //         }
    //       }

    //       $text_noti = strtoupper($user_emisor->username).' '.$estatus_negociacion;
    //       $this->save_notification($user_contraparte->id,$tittle_noti, $text_noti, $negociacion->idposturamatch,1);
    //       $message = "La negociación se ha guardado con exito";
    //     }else{
    //       $message = "La negociación no se ha podido guardar con exito";
    //       error_log($message);
    //     }

    //     return response()->json([
    //         'code'      => $code,
    //         'msg'   => $message,
    //         'data'      => $data
    //     ],200);

    // }

    public function saveComprobante(Request $request)
    {
      error_log("===============saveComprobante NEGOCIACION========");
      $code       = "OK";
      $message    = "";
      $data       = [];

      $idNegociacion = $request->idNeg;
      $idUser = $request->idUser;
      $idNegContraparte = $request->idNegContraparte;
      error_log('$idNegociacion = '.$idNegociacion.' $idUser = '.$idUser.' $idNegContraparte = '.$idNegContraparte);
      if($request->hasFile('comprobante')){
          $comprobante = $request->file('comprobante')->store('public/evidenciasNegociacion');
      }

      if(negociacion::where('id',$idNegociacion)->update(['comprobante' => $comprobante,'estatusnegociacion' => $request->status]) && negociacion::where('id',$idNegContraparte)->update(['estatusnegociacion' => $request->status]))
      {
        error_log('inicializo vriables para status not');
        // Notifico a backoffice
        $user_backoffice = 5;
        $backoffices = User::where('tipousuario_idtipousuario',$user_backoffice)->get();
        $negociacion = negociacion::find($idNegociacion);
        $user_emisor = User::find($negociacion->iduser);
        error_log('inicializadas  backoffices y negociacion');
        $estatus_negociacion = 'agregó el comprobante de transferencia';
        $tittle_noti = 'Negociación';
        error_log('antes de enviar not a back for');
        $text_noti = strtoupper($user_emisor->username).' '.$estatus_negociacion;
        error_log('foreach ($backoffices as $backoffice)');
        foreach ($backoffices as $backoffice)
        {
          error_log('antes de enviar not a '.$backoffice->username);
          $this->save_notification($backoffice->id,$tittle_noti, $text_noti, $negociacion->idposturamatch,1);
          error_log('despues de enviar not a '.$backoffice->username);
        }

        $code       = "OK";
        $message    = "El comprobante se subió de forma exitosa";
        $data = 1;

      }
      else
      {
        $code  = "NOTOK";
        $message = "Ocurrio un problema al intentar guardar el archivo";
        $data = 0;
      }  
      

      return response()->json([
      'code'  => $code,
      'msg'   => $message,
      'data'  => $data
      ],200);
        
    }

    public function confirmacionTransferenciaBackoffice($idNeg,$idNegContraparte,$estatus){
      
      error_log('================confirmacionTransferenciaBackoffice==================='.$idNeg.','.$idNegContraparte.','.$estatus);            
      $code       = "OK";
      $message    = "";
      $data       = [];

      $negociacion = negociacion::find($idNeg);
      $negociacion_contraparte = negociacion::find($idNegContraparte);

      $negociacion->estatus_autoriza_backoffice = $estatus;
      $negociacion_contraparte->estatus_autoriza_backoffice = $estatus;


      if($negociacion->save() && $negociacion_contraparte->save())
      {
        $user_username = strtoupper(User::find($negociacion->iduser)->username);
        // Esta condicion debo modificarla a su numero correspondiente sin restar 1, pero para ello debo actualizar condiciones en la operacion tambien
        $estatus_negociacion = estatusNegociacion::find($negociacion->estatusnegociacion-1)->estatus;
        $tittle_noti = 'Negociación';
        $text_noti = $user_username.' '.$estatus_negociacion;

        $this->save_notification($negociacion_contraparte->iduser,$tittle_noti, $text_noti, $negociacion->idposturamatch,1);
        
        $code       = "OK";
        $message    = "El estatus se ha actualizado de forma exitosa";
        $data = 1;
      }
      else
      {
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

    public function confirmacionTransferencia($miIdNeg,$idNegContraparte,$estatus){
      error_log('================confirmacionTransferencia==================='.$miIdNeg.','.$idNegContraparte.','.$estatus);            
      $code       = "OK";
      $message    = "";
      $data       = [];

      $status_a_notificar_contraparte = [3,5];

      $negociacion = negociacion::find($miIdNeg);
      $negociacion->estatusnegociacion = $estatus;

      $negociacion_contraparte = negociacion::find($idNegContraparte);
      $negociacion_contraparte->estatusnegociacion = $estatus;

      if( $negociacion->save() && $negociacion_contraparte->save())
      {
        if(in_array($negociacion->estatusnegociacion,$status_a_notificar_contraparte))
        {
          $user_emisor = User::find($negociacion->iduser);
          $user_contraparte = User::find($negociacion_contraparte->iduser);

          $user_backoffice = 5;
          $backoffices = User::where('tipousuario_idtipousuario',$user_backoffice)->get();

          // Esta condicion debo modificarla a su numero correspondiente sin restar 1, pero para ello debo actualizar condiciones en la operacion tambien
          $estatus_negociacion = estatusNegociacion::find($negociacion->estatusnegociacion-1)->estatus;;
          $tittle_noti = 'Negociación';
          $text_noti = strtoupper($user_emisor->username).' '.$estatus_negociacion;

          // Notifico a contraparte
          $this->save_notification($user_contraparte->id,$tittle_noti, $text_noti, $negociacion->idposturamatch,1);

          // Notifico a backoffice
          foreach ($backoffices as $backoffice)
          {
            $this->save_notification($backoffice->id,$tittle_noti, $text_noti, $negociacion->idposturamatch,1);
          }

          if($negociacion->estatusnegociacion == 5)
          {
            $postura_concretada = 5;
            $operacion_concretada = 3;
            $postura_match = posturasMatches::find($negociacion->idposturamatch);

            $postura_match->update(['estatusOperaciones_idestatusOperacion' => $operacion_concretada]);

            postura::where('idposturas',$postura_match->posturas_idposturas)->first()->update(['estatusposturas_idestatusposturas' => $postura_concretada]);
            postura::where('idposturas',$postura_match->postura_contraparte_id)->first()->update(['estatusposturas_idestatusposturas' => $postura_concretada]);
          }
        }

        $code       = "OK";
        $message    = "El estatus se ha actualizado de forma exitosa";
        $data = 1;
      }
      else
      {
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

    //Método para guardar notificaciones
    private function save_notification($iduser,$tittle, $text,$postura_match_id,$status)
    {
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
        $not_has_user->notificaciones_idnotificaciones = $notificacion->id;
        $not_has_user->postura_match_id = $postura_match_id;
        
        if($not_has_user->save())
        {
            $resp = true;
        }            
      }
      return $resp;
    }

    public function confirmacion1($iduser,$idposturamatch){
            error_log('===================confirmacion1======================');
            error_log('$iduser = '.$iduser.'  /  $idposturamatch = '.$idposturamatch);
            $code       = "OK";
            $message    = "";
            $data       = [];

            if(
                    negociacion::where('idposturamatch',$idposturamatch)
                            ->where('iduser',$iduser)
                            ->update(['estatusnegociacion' => 3]) && 
                    negociacion::where('idposturamatch',$idposturamatch)
                            ->where('iduser','!=',$iduser)
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

    public function confirmacion2($iduser,$idposturamatch){
            error_log('===================confirmacion2======================');
            error_log('$iduser = '.$iduser.'  /  $idposturamatch = '.$idposturamatch);            
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
        error_log('===================confirmacion3======================');
        error_log('$iduser = '.$iduser.'  /  $idposturamatch = '.$idposturamatch);
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
        error_log('===================confirmacion4======================');
        error_log('$iduser = '.$iduser.'  /  $idposturamatch = '.$idposturamatch);
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
