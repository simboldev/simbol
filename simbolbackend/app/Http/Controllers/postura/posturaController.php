<?php

namespace App\Http\Controllers\postura;

use DB;
use App\moneda;
use App\postura;
use App\posturasMatche;
use App\User;
use App\posturas_rechazadas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\nuevaPostura;
use Illuminate\Support\Facades\Mail;

class posturaController extends Controller
{
    var $code       = '';
    var $message    = '';
    var $data       = [];
    var $array_posturas_match_id  = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // return view('front.posturas.posturas');

        $code       = "OK";
        $message    = "Success";
        $data       = DB::table('posturas as post')
            ->select('post.*')
            ->orderBy('post.idposturas', 'desc')
            ->get();

        return response()->json([
            'code'=> $code,
            'message' => $message,
            'data'=> $data
        ],
        200);
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
      $data = [];
      $postura_data = $request->all();
      $rules = [
                'quiero_moneda_id'      => 'required',
                'tengo_moneda_id'       => 'required',
                'tengo'                 => 'required',
                'quiero'                => 'required',
                'tasacambio'            => 'required',
                'fechadesde'            => 'required',
                'fechahasta'            => 'required',
                'iduser'                => 'required',
                'estatusPosturas_idestatusPosturas' => 'required',
                'fraccionar'            => 'required',
                'bancos_bs'             => 'required',
                'bancos_usd'            => 'required'
              ];

        $validator = \Validator::make($postura_data, $rules);
        if ($validator->fails())
        {
          $code       = "NOTOK";
          $message    = $validator->errors()->all();
        }
        else
        {
          $postura = new \App\postura($postura_data);
          if( $postura->save())
          {
            $this->save_posturas_bancos($postura->id,$postura_data['bancos_bs']);
            $this->save_posturas_bancos($postura->id,$postura_data['bancos_usd']);

            //notificacion via email
            // $email = User::select('email')->where('id','=',$postura->iduser)->get();

            /*DESCOMENTAR LUEGO
            $email = env('APP_SIMBOL_MAIL');
            Mail::to($email)
                ->send(new nuevaPostura($postura));*/

            $code       = "OK";
            $message    = "";
            $data       = $postura->id;
          }
          else
          {
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar guardar la postura";
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
        $code       = "OK";
        $message    = "";
        $data       = [];

        if( is_numeric($id))
        {
            $postura = Postura::where('idposturas', '=' ,$id)->get();
            
            $operaciones_ejecutadas = self::
            get_operaciones_user($postura[0]['iduser'],'count');

            // if( $operaciones_ejecutadas > 0)
            // {
            //     $volumen_min = self::
            // get_operaciones_user($postura[0]['iduser'],'volumen_min');

            //     $volumen_max = self::
            // get_operaciones_user($postura[0]['iduser'],'volumen_max');
            // }
            // else
            // {
            //     $volumen_min = 0;
            //     $volumen_max = 0;
            // }

            $postura[0]->quiero_moneda_simbolo = 
            moneda::select('admin_simbolo')->where('idmonedas','=',$postura[0]['quiero_moneda_id'])->get()[0]['admin_simbolo'];
            $postura[0]->tengo_moneda_simbolo = 
            moneda::select('admin_simbolo')->where('idmonedas','=',$postura[0]['tengo_moneda_id'])->get()[0]['admin_simbolo'];
            $postura[0]->user = User::select('id','username')->where('id','=',$postura[0]['iduser'])->get()[0];
            $postura[0]->user->volumen = 1000;
            $postura[0]->user->operaciones = array('ejecutadas' => $operaciones_ejecutadas
            ,'primera' => self::get_operaciones_user($postura[0]['iduser'],'first')
            ,'ultima' => self::get_operaciones_user($postura[0]['iduser'],'last')
            );
            
            $postura[0]->bancos = self::get_bancos_postura($id,0);
            $this->data = $postura;
        }
        else
        {
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        return response()->json([
            'code'      => $code,
            'message'   => $message,
            'data'      => $this->data
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

    // Se almacenan todos los bancos que selecciono el usuario para una postura
    public function save_posturas_bancos($id_postura,$bancos)
    {
        for ($i=0; $i< count($bancos); $i++)
        {
            $obj_post_banco = array(
                "posturas_id"=>$id_postura,
                "users_bancos_pais_monedas_id"=>$bancos[$i]);

            $posturas_banco = new \App\posturas_has_users_bancos($obj_post_banco);
            $posturas_banco->save();
        }
    }

    // Posturas macth
    public function posturas_macth($id_postura,$nro_posturas,$pagina)
    {
        error_log('============== posturas_macth ==============');
        $postura = array();
        $postura = Postura::where('idposturas', '=' ,$id_postura)->get();
        $posturas_macthes_gral = array();
        
        // Si hay resultados en la consulta
        if( count($postura) != 0 )
        {
            $posturas_tasa_monto_bancos = array();
            $array_amigos_id = self::get_array_amigos_id($postura[0]['iduser']);

            // Si la postura no se puede fraccionar
            if( $postura[0]['fraccionar'] == 0 )
            {
                // a. Encolar aquellas posturas de usuarios que forman parte del círculo de confianza, ordenándose internamente de la siguiente manera:

                // i. Encolar aquellas posturas que coinciden en: Tasa de cambio, Monto en dólares y Bancos. Ordenándose de forma FIFO.
                
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, false, '=', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // ii. Encolar aquellas posturas que coinciden con la tasa de cambio y los bancos, sin embargo, el monto en dólares es mayor al requerido, y por ende sólo se satisfará una porción de la demanda del otro usuario. Ordenándose de forma FIFO (esto solo ocurre si la postura del otro usuario permite que se pueda fraccionar)
                
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, true, '>', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // iii. Encolar aquellas posturas que coinciden con la tasa de cambio, pero no así con los bancos. Ordenándose dependiendo del monto en dólares tal como se describe en los puntos I, II. Ordenándose de forma FIFO

                // punto i
                
                // consulta_posturas_tasa_monto_bancos($flag_amigos, $array_amigos_id, $flag_tasacambio, $flag_fraccionar, $option_monto, $flag_banco, $postura, $nro_posturas, $pagina)

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, false, '=', false, $postura,$nro_posturas,$pagina);

                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // punto ii
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, true, '>', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // ======================================== //
                
                // b. Encolar aquellas posturas de usuarios que  NO forman parte del círculo de confianza, ordenándose internamente como se indicó en los puntos i, ii y iii

                // i. Encolar aquellas posturas que coinciden en: Tasa de cambio, Monto en dólares y Bancos. Ordenándose de forma FIFO.
                
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, false, '=', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // ii. Encolar aquellas posturas que coinciden con la tasa de cambio y los bancos, sin embargo, el monto en dólares es mayor al requerido, y por ende sólo se satisfará una porción de la demanda del otro usuario. Ordenándose de forma FIFO (esto solo ocurre si la postura del otro usuario permite que se pueda fraccionar)
                
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, true, '>', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // iii. Encolar aquellas posturas que coinciden con la tasa de cambio, pero no así con los bancos. Ordenándose dependiendo del monto en dólares tal como se describe en los puntos I, II. Ordenándose de forma FIFO

                
                // punto i
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, false, '=', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);
                
                // BIEN
                // punto ii
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, true, '>', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);
            }
            else // Si la postura se puede fraccionar
            {
                // a. Encolar aquellas posturas de usuarios que forman parte del círculo de confianza, ordenándose internamente de la siguiente manera:

                // i. Encolar aquellas posturas que coinciden en: Tasa de cambio, Monto en dólares y Bancos. Ordenándose de forma FIFO.

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, false, '=', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // ii. Encolar aquellas posturas que coinciden con la tasa de cambio y los bancos, sin embargo, el monto en dólares es mayor al requerido, y por ende sólo se satisfará una porción de la demanda del otro usuario. Ordenándose de forma FIFO
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, true, '>', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // iii. Encolar aquellas posturas que coinciden con la tasa de cambio y los bancos, sin embargo, el monto en dólares es menor al requerido, y por ende sólo se satisfará una porción de la demanda del usuario. Ordenándose de forma FIFO

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, true, '<', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // Posturas no fraccionadas
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, false, '<', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // iv. Encolar aquellas posturas que coinciden con la tasa de cambio, pero no así con los bancos. Ordenándose dependiendo del monto en dólares tal como se describe en los puntos I, II y III. Ordenándose de forma FIFO

                // punto i
                // consulta_posturas_tasa_monto_bancos($flag_amigos, $array_amigos_id, $flag_tasacambio, $flag_fraccionar, $option_monto, $flag_banco, $postura, $nro_posturas, $pagina)

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, false, '=', false, $postura,$nro_posturas,$pagina);

                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // punto ii
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, true, '>', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // punto iii
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, true, '<', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // Posturas no fraccionadas
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(true, $array_amigos_id, true, false, '<', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // ======================================== //

                // b. Encolar aquellas posturas de usuarios que NO forman parte del círculo de confianza, ordenándose internamente como se indicó en los puntos i, ii, iii y iv

                // i. Encolar aquellas posturas que coinciden en: Tasa de cambio, Monto en dólares y Bancos. Ordenándose de forma FIFO.

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, false, '=', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // ii. Encolar aquellas posturas que coinciden con la tasa de cambio y los bancos, sin embargo, el monto en dólares es mayor al requerido, y por ende sólo se satisfará una porción de la demanda del otro usuario. Ordenándose de forma FIFO
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, true, '>', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // iii. Encolar aquellas posturas que coinciden con la tasa de cambio y los bancos, sin embargo, el monto en dólares es menor al requerido, y por ende sólo se satisfará una porción de la demanda del usuario. Ordenándose de forma FIFO

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, true, '<', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // Posturas no fraccionadas
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, false, '<', true, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // iv. Encolar aquellas posturas que coinciden con la tasa de cambio, pero no así con los bancos. Ordenándose dependiendo del monto en dólares tal como se describe en los puntos I, II y III. Ordenándose de forma FIFO

                // punto i
                // consulta_posturas_tasa_monto_bancos($flag_amigos, $array_amigos_id, $flag_tasacambio, $flag_fraccionar, $option_monto, $flag_banco, $postura, $nro_posturas, $pagina)

                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, false, '=', false, $postura,$nro_posturas,$pagina);

                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // punto ii
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, true, '>', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // punto iii
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, true, '<', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);

                // Posturas no fraccionadas
                $posturas_tasa_monto_bancos = self::consulta_posturas_tasa_monto_bancos(false, $array_amigos_id, true, false, '<', false, $postura,$nro_posturas,$pagina);
                $posturas_macthes_gral = self::actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral);
            }

            $code       = "OK";
            $message    = "Success";
            $data       = $posturas_macthes_gral;
        }
        else // si no hay resultados en la consulta
        {
            $code       = "OK";
            $message    = "Consulta sin resultados";
            $data       = [];
        }

        return response()->json([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data
        ],200);
    }

    private function get_array_amigos_id($id_user)
    {
        $amigos = DB::table('amigos')->where('user1', '=', $id_user)->orWhere('user2', '=', $id_user)->get();

        $amigos_array = array();

        foreach ($amigos as $amigo)
        {
            $add_id = 0;
            // Se valida que no se agregue el id del usuario que se consultan sus amigos
            if($id_user != $amigo->user1)
                $add_id = $amigo->user1;

            if($id_user != $amigo->user2)
                $add_id = $amigo->user2;

            // Se agrega iduser de amigo a $amigos_array si el mismo no ha sido agregado anteriormente
            if( !in_array($add_id, $amigos_array))
            {
                array_push($amigos_array,$add_id);
            }
        }

        return $amigos_array;
    }

    /**
     * Se obtienen un arreglo con los 
     * id de las posturas que he rechazado o id
     * @param  array[jsons]  $bancos
     */
    private function get_posturas_rechazadas($postura)
    {
        $posturas_rechazadas = DB::table('posturas_rechazadas as post_rech')
            ->where('post_rech.mi_postura_id','=',$postura[0]['idposturas'])
            ->orWhere('post_rech.postura_rechazada_id','=',$postura[0]['idposturas'])
            ->orderBy('post_rech.id', 'asc')->get();

        $posturas_rechazadas_array = array();

        foreach ($posturas_rechazadas as $postura_rechazada)
        {
            $add_id = 0;
            // Se valida que no se agregue el id del postura que estoy consultando
            if($postura[0]['idposturas'] != $postura_rechazada->mi_postura_id)
                $add_id = $postura_rechazada->mi_postura_id;

            if($postura[0]['idposturas'] != $postura_rechazada->postura_rechazada_id)
                $add_id = $postura_rechazada->postura_rechazada_id;

            // Se agrega id de $postura rechazada si el mismo no ha sido agregado anteriormente
            if( !in_array($add_id, $posturas_rechazadas_array))
            {
                array_push($posturas_rechazadas_array,$add_id);
            }
        }

        return $posturas_rechazadas_array;
    }

    /**
     * Se obtienen un arreglo con los 
     * idmonedas de los bancos que posee una postura
     * @param  array[jsons]  $bancos
     */
    private function get_array_bancos_postura($bancos,$columna)
    {

        $bancos_array = array();

        foreach ($bancos as $banco)
        {
            // Se agrega  de idmonedas del objeto banco a $bancos_array si el mismo no ha sido agregado anteriormente
            if( !in_array($banco->$columna, $bancos_array))
            {
                array_push($bancos_array,$banco->$columna);
            }
        }
        return $bancos_array;
    }

    /**
     * Lista todos los bancos de una postura.
     * Ejempo:
     *  [
     *      {
     *          "idposturas_has_users_bancos": 26,
     *          "idusers_bancos_pais_monedas": 1,
     *          "nombre_banco": "Banco de Venezuela",
     *          "idmonedas": 1,
     *          "nombremoneda": "Bolivar",
     *          "idpais": 1,
     *          "pais": "Venezuela"
     *      },
     *      .
     *      .
     *      .
     *  ]
    */
    private function get_bancos_postura($postura_id,$moneda_id)
    {
        $bancos = DB::table('posturas_has_users_bancos as phub')
            ->join('users_bancos_pais_monedas as ubpm', 'ubpm.idusers_bancos_pais_monedas', '=', 'phub.users_bancos_pais_monedas_id')
            ->join('bancos_pais_monedas as bpm', 'bpm.idbancos_pais_monedas', '=', 'ubpm.idbancos_pais_monedas')
            ->join('bancos', 'bpm.idbanco', '=', 'bancos.idbancos')
            ->join('monedas as mon', 'bpm.idmoneda', '=', 'mon.idmonedas')
            ->join('pais', 'bpm.idpais', '=', 'pais.idpais')
            ->select('phub.idposturas_has_users_bancos', 'ubpm.idusers_bancos_pais_monedas', 'ubpm.idbancos_pais_monedas', 'bancos.nombre as nombre_banco', 'mon.idmonedas', 'mon.nombremoneda','pais.idpais', 'pais.nombre as pais')
            ->where('phub.posturas_id', '=', $postura_id);
            
            if($moneda_id > 0)
                $bancos->where('mon.idmonedas', '=', $moneda_id);

            $bancos->orderBy('mon.nombremoneda', 'asc');

        return $bancos->distinct()->get();
    }

    /**
     *
     */
    private function consulta_posturas_tasa($postura,$m,$n,$res)
    {
       // Posturas que hacen macth coincidiendo la tasa de cambio
        $posturas_macth = DB::table('posturas as post')
            ->where('post.tasacambio', '=', $postura[0]['tasacambio'])
            ->where('post.iduser', '!=', $postura[0]['iduser'])
            ->whereBetween('post.fechadesde', [$postura[0]['fechadesde'],$postura[0]['fechahasta']])
            ->select('post.*')
            ->orderBy('post.idposturas', 'asc')
            ->offset($res)
            ->limit($m)
            ->get();
            // Retorna un máximo de m registros a partir del registro res

            return $posturas_macth;
    }

    /**
     *    Posturas que hacen macth coincidiendo la tasa * de cambio, monto en usd y bancos
     *
     * @param  int    $flag_amigos
     * @param  array  $array_amigos_id
     * @param  obj    $postura
     * @return obj    $posturas_macth
     */

    private function consulta_posturas_tasa_monto_bancos($flag_amigos, $array_amigos_id, $flag_tasacambio, $flag_fraccionar, $option_monto, $flag_banco, $postura, $nro_posturas, $pagina)
    {
        $posturas_macth = [];
        $m = $nro_posturas; // cantidad de posturas a mostrar por página
        $n = $pagina; // nro. de página a consultar
        $res = ($m)*($n-1);
        $status_postura = 1;
        $porcentaje = 0.1;
        $porcentaje_postura = $postura[0]['tasacambio']*$porcentaje;
        $tasa_min = $postura[0]['tasacambio']-$porcentaje_postura;
        $tasa_max = $postura[0]['tasacambio']+$porcentaje_postura;

        $array_posturas_rechazadas = self::get_posturas_rechazadas($postura);

        $query = DB::table('posturas as post')
            ->join('posturas_has_users_bancos as phub', 'post.idposturas', '=', 'phub.posturas_id')
            ->where('post.quiero_moneda_id', '=', $postura[0]['tengo_moneda_id'])
            ->where('post.tengo_moneda_id', '=', $postura[0]['quiero_moneda_id'])
            ->where('post.iduser', '!=', $postura[0]['iduser'])
            ->where('post.estatusPosturas_idestatusPosturas','=',$status_postura)
            ->whereBetween('post.tasacambio', [$tasa_min,$tasa_max])
            ->whereBetween('post.fechadesde', [$postura[0]['fechadesde'],$postura[0]['fechahasta']])
            ->where('post.quiero', $option_monto, $postura[0]['tengo'])
            ->whereNotIn('post.idposturas', $array_posturas_rechazadas);

        error_log('============== flag_amigos ==============');
        if($flag_amigos == true)
        {
            error_log($flag_amigos);
            $query->whereIn('post.iduser', $array_amigos_id);
        }
        else
        {
            error_log($flag_amigos);
            $query -> whereNotIn('post.iduser', $array_amigos_id);
        }

        // Cuando se exige que monto coincida
        // if($flag_tasacambio == true)
        // {
        //     $query -> where('post.tasacambio', '=', $postura[0]['tasacambio']);
        // }

        /*
         * Solo ocurre si la postura del otro
         * usuario permite que se pueda fraccionar
        */
        if( $flag_fraccionar == true)
        {
            $query ->where('post.fraccionar','=',1);
        }

        $query ->select('post.*')
            -> orderBy('post.idposturas', 'asc')
            ->offset($res)
            ->limit($m);
            // Retorna un máximo de m registros a partir del registro res

        $posturas_macth = $query->distinct()->get();

        $posturas_macth = self::add_bancos_a_posturas_json($postura, $posturas_macth,$flag_banco);
        
        return $posturas_macth;
    }


    private function add_bancos_a_posturas_json($postura, $posturas_macth,$flag_banco)
    {
        $posturas_macth_nvo = array();
        $bancos_quiero_postura = self::get_bancos_postura($postura[0]['idposturas'],$postura[0]['quiero_moneda_id']);

        $bancos_tengo_postura = self::get_bancos_postura($postura[0]['idposturas'],$postura[0]['tengo_moneda_id']);

        $i=0;
        foreach ($posturas_macth as $postura_macth)
        {
            $cont_quiero_moneda_id  = 0;
            $cont_tengo_moneda_id = 0;
            $posturas_has_users_bancos_id = [];
            $bancos_postura_macth = self::get_bancos_postura($postura_macth->idposturas,0);
            $array_idbancos_pais_monedas_macth = self::get_array_bancos_postura($bancos_postura_macth,'idbancos_pais_monedas');

            /* 
             *   Validar si al menos un banco 
             * de cada moneda de la $postura_macth coincide
             * con alguno de los bancos de la $postura con
             * la que hace un previo match
            */

            foreach ($bancos_quiero_postura as $banco_postura)
            {
                if( in_array($banco_postura->idbancos_pais_monedas, $array_idbancos_pais_monedas_macth))
                {
                    $cont_quiero_moneda_id += 1;
                }
            }

            foreach ($bancos_tengo_postura as $banco_postura)
            {
                if( in_array($banco_postura->idbancos_pais_monedas, $array_idbancos_pais_monedas_macth))
                {
                    $cont_tengo_moneda_id += 1;
                }
            }

            if( (($cont_quiero_moneda_id > 0) && ($cont_tengo_moneda_id > 0) && $flag_banco) || (($cont_quiero_moneda_id == 0) && ($cont_tengo_moneda_id == 0) && !$flag_banco) || (($cont_quiero_moneda_id > 0) || ($cont_tengo_moneda_id > 0) && $flag_banco))
            {
                // agrego quiero_moneda_simbolo a su postura
                $postura_macth->quiero_moneda_simbolo = moneda::select('admin_simbolo')->where('idmonedas','=',$postura_macth->quiero_moneda_id)->get()[0]['admin_simbolo'];
                // agrego tengo_moneda_simbolo a su postura
                $postura_macth->tengo_moneda_simbolo = moneda::select('admin_simbolo')->where('idmonedas','=',$postura_macth->tengo_moneda_id)->get()[0]['admin_simbolo'];
                // agrego informacion del usuario que creo la postura
                $postura_macth->user = User::select('id','username')->where('id','=',$postura_macth->iduser)->get()[0];

                // agrego el listado de bancos a su postura
                $postura_macth->bancos = $bancos_postura_macth;
                array_push($posturas_macth_nvo,$postura_macth);
            }
            else
            {
                // elimino la postura de la lista
                // if(count($posturas_macth) == 1)
                // {
                //     $posturas_macth = [];
                // }
                // else
                // {
                //     // $borrar =  $posturas_macth()[$i];
                //     // unset( $borrar);
                // }
            }
            $i++;
        } // end foreach

        return $posturas_macth_nvo;
    }

    private function get_operaciones_user($iduser,$condicion)
    {
        $concretada = 3;

        // DB::table('posturas')
        // ->where('iduser', '=', $iduser)

        if($condicion == 'first')
        {
            $query_operaciones_user = 
            DB::table('posturas_matches')
            ->where('users_idusers', '=', $iduser)
            ->where('estatusOperaciones_idestatusOperacion', '=',$concretada)
            ->first();
        }

        if($condicion == 'last')
        { 
            $query_operaciones_user = 
            DB::table('posturas_matches')
            ->where('users_idusers', '=', $iduser)
            ->where('estatusOperaciones_idestatusOperacion', '=',$concretada)
            ->latest()
            ->first();
        }

        if($condicion == 'count')
        {
            $query_operaciones_user = 
            DB::table('posturas_matches')
            ->where('users_idusers', '=', $iduser)
            ->where('estatusOperaciones_idestatusOperacion', '=',$concretada)
            ->count();
        }

        if($condicion == 'all')
        {
            $query_operaciones_user = 
            DB::table('posturas_matches')
            ->where('users_idusers', '=', $iduser)
            ->where('estatusOperaciones_idestatusOperacion', '=',$concretada)->distinct()->get();
        }

        return $query_operaciones_user;
    }

    // private function get_volumen_operations_user($iduser,$condicion)
    // {
    //     $concretada = 3;
    //     $moneda_id = 2;
    //     $tengo  = 0;
    //     $quiero = 0;

    //     if($condicion == 'volumen_min')
    //     {
    //         $query_operaciones_user = 
    //         DB::table('posturas_matches')
    //         ->where('users_idusers', '=', $iduser)
    //         ->where('estatusOperaciones_idestatusOperacion', '=',$concretada)
    //         ->min();
    //     }

    //     if($condicion == 'volumen_max')
    //     {
    //         $query_operaciones_user = 
    //         DB::table('posturas as p')
    //         ->join('posturas_matches as pm', 'pm.posturas_idposturas', '=', 'p.idposturas')
    //         ->select('p.tengo','p.quiero')
    //         ->where('p.quiero_moneda_id', '=', $moneda_id)
    //         ->orWhere('p.tengo_moneda_id','=',$moneda_id)
    //         ->where('pm.users_idusers', '=', $iduser)
    //         ->orWhere('pm.iduser2','=',$iduser)
    //         ->where('pm.estatusOperaciones_idestatusOperacion', '=',$concretada)
    //         ->get();

    //         if( count($query_operaciones_user) > 0)
    //         {
    //             $tengo  = (float) $query_operaciones_user[0]->tengo;
    //             $quiero = (float) $query_operaciones_user[0]->quiero;

    //             if ($tengo > $quiero)
    //             {
    //                 $val = $tengo;
    //             }
    //             else
    //             {
    //                 $val = $quiero;
    //             }
    //         }

    //         return $val;
    //     }

    //     return $query_operaciones_user;
    // }

    private function inicializar_posturas_match_id($posturas_macthes)
    {
        $this->array_posturas_match_id = array();

        foreach ($posturas_macthes as $postura_macth)
        {
            // Se agrega idposturas a $posturas_macthes si el mismo no ha sido agregado anteriormente
            if( !in_array($postura_macth->idposturas, $this->array_posturas_match_id))
            {
                array_push($this->array_posturas_match_id,$postura_macth->idposturas);
            }
        }
    }

    private function actualizar_filtro_posturas($posturas_tasa_monto_bancos, $posturas_macthes_gral)
    {
        error_log('============== actualizar_filtro_posturas ==============');
        error_log(count($posturas_tasa_monto_bancos));
        error_log(count($this->array_posturas_match_id));
        error_log(count($posturas_macthes_gral));

        if((count($posturas_tasa_monto_bancos) > 0) && (count($this->array_posturas_match_id) == 0) && (count($posturas_macthes_gral) == 0))
        {
            error_log('if');
            self::inicializar_posturas_match_id($posturas_tasa_monto_bancos);

            $posturas_macthes_gral = $posturas_tasa_monto_bancos;
        }
        else if((count($posturas_tasa_monto_bancos) > 0) && (count($this->array_posturas_match_id) > 0) && (count($posturas_macthes_gral) > 0))
        {
            error_log('elseif');
            $posturas_macthes_gral = self::filtrar_posturas_matches($posturas_macthes_gral, $posturas_tasa_monto_bancos);
        }
        return $posturas_macthes_gral;
    }

    private function filtrar_posturas_matches($posturas_macthes_gral, $posturas_macthes_actual)
    {
        $i=0;
        foreach ($posturas_macthes_actual as $postura_macth)
        {
            // array_push($posturas_macthes_gral,$postura_macth);
            // Se agrega idposturas a $posturas_macthes si el mismo no ha sido agregado anteriormente
            if( !in_array($postura_macth->idposturas, $this->array_posturas_match_id))
            {
                array_push($this->array_posturas_match_id,$postura_macth->idposturas);
                array_push($posturas_macthes_gral,$postura_macth);
            }
            else
            {
                // Borro el registro
                $borrar =  $posturas_macthes_actual[$i];
                unset($borrar);
            }
            $i++;
        }
        return $posturas_macthes_gral;
    }

    public function cambiar_estatus_postura($idpostura,$status)
    {
        try
        {
            $query = DB::table('posturas')
                ->where('idposturas', $idpostura)
                ->update(['estatusPosturas_idestatusPosturas' => $status]);

            switch ($query)
            {
                case 0:
                    $this->code = "OK";
                    $this->message = "La postura ya se encuentra en el estatus solicitado.";
                    $this->data = 0;
                    break;
                case 1:
                    $this->code = "OK";
                    $this->data = 1;
                    break;
                default:
                    $this->code       = "NOTOK";
                    $this->message    = "Error al intentar actualizar la data, intente nuevamente.";
                    $this->data = 3;
                    break;
            }
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->code       = "NOTOK";
            $this->message    = "Error al intentar actualizar la data, intente nuevamente.";
        }

        return response()->json([
            'code'      => $this->code,
            'message'   => $this->message,
            'data'      => $this->data
        ],
        200);
    }

    public function cambiar_estatus_operacion($idpostura,$status)
    {
        try
        {
            $query = DB::table('posturas_matches')
                ->where('idposturasMatch', $idpostura)
                ->update(['estatusOperaciones_idestatusOperacion' => $status]);

            switch ($query)
            {
                case 0:
                    $this->code = "OK";
                    $this->message = "La operación ya se encuentra en el estatus solicitado.";
                    break;
                case 1:
                    $this->code = "OK";
                    break;
                default:
                    $this->code       = "NOTOK";
                    $this->message    = "Error al intentar actualizar la data, intente nuevamente.";
                    break;
            }
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $this->code       = "NOTOK";
            $this->message    = "Error al intentar actualizar la data, intente nuevamente.";
        }

        return response()->json([
            'code'      => $this->code,
            'message'   => $this->message,
            'data'      => $this->data
        ],
        200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lista_posturas($id_user, $status_postura,$order_by,$nro_posturas,$pagina)
    {        
        $code       = "OK";
        $message    = "Success";

        $m = $nro_posturas; // cantidad de posturas a mostrar por página
        $n = $pagina; // nro. de página a consultar
        $res = ($m)*($n-1);

        $query      = DB::table('posturas as post')
            ->select('post.*');
        
        if($id_user > 0) 
        {
            // Devuelve las posturas de un usuario, de lo contrario retorna todas las posturas
            $query->where('post.iduser','=',$id_user);
        }
        
        if($status_postura > 0)
        {
            $query->where('post.estatusPosturas_idestatusPosturas','=',$status_postura);
        }
        
        if($order_by == 0)
            $query->orderBy('post.idposturas', 'asc');
        else
            $query->orderBy('post.idposturas', 'desc');

        if($nro_posturas > 0 && $pagina > 0)
        {
            $query->offset($res);
            $query->limit($m);
            // Retorna un máximo de m registros a partir del registro res
        }

        $this->data = $query->distinct()->get();

        foreach ($this->data as $postura)
        {
            $postura->quiero_moneda_simbolo = 
                moneda::select('admin_simbolo')->where('idmonedas','=',$postura->quiero_moneda_id)->get()[0]['admin_simbolo'];
            $postura->tengo_moneda_simbolo = 
                moneda::select('admin_simbolo')->where('idmonedas','=',$postura->tengo_moneda_id)->get()[0]['admin_simbolo'];
            $postura->user = User::select('id','username')->where('id','=',$postura->iduser)->get()[0];
        }

        return response()->json([
            'code'=> $code,
            'message' => $message,
            'data'=> $this->data
        ],
        200);
    }

    //Método para traer las posturas en la interfaz inicial
    public function posturasIndex(){
        $code       = "OK";
        $message    = "Success";
        $data       = [];

        $posturas = postura::select(
            'quiero_moneda_id',
            'idposturas',
            'tasacambio',
            'tengo',
            'fechahasta'
        )
        ->orderBy('fechahasta','desc')
        ->limit(10)
        ->get();

        if(!$posturas){
            $code = "NOTOK";
            $message = "La consulta de posturas no arrojo nada";
        }

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $posturas
        ],200);

    }
}
