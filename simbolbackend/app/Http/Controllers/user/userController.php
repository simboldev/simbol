<?php

namespace App\Http\Controllers\user;

use DB;
use App\User;
use App\moneda;
use App\Mail\emailLogin;
use App\Mail\emailRecPass;
use App\Mail\emailTracking;
use App\Mail\emailRecPassSuccess;
use App\users_bancos_pais_monedas;
use App\postura;
use App\estatusOperacion;
use App\log_user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class userController extends Controller
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
        $code       = "OK";
        $message    = "Success";

        $data       = User::get();
       
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
        $code       = "OK";
        $message    = "";
        $data       = [];

        if( is_numeric($id))
        {
            $user = User::find($id);
   
        }
        else
        {
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
       
        }    

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $user
        ],200);
    }


//Método que suplanta al anterior show ya que el anterior no funciona
    public function shows($id,$id_id){
        $code       = "OK";
        $message    = "";
        $data       = [];

        if( is_numeric($id))
        {
            $user = User::find($id);
        }
        else
        {
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }    
 
        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $user
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
    public function update(Request $request, User $users)
    {
        //
        $code       = "OK";
        $message    = "";
        $data       = [];

        /*$users->id = $request->id;
        $users->fill($request->all());*/

        $users=User::select(
            'password',
            'remember_token',
            'email',
            'estatususuario',
            'recomendado_por_user_id',
            'tipousuario_idtipousuario',
            'verification_token',
            'online')
        ->where('id','=',$request->id)
        ->get();

        $users[0]->id=$request->id;
        $users[0]->password = $request->password;
        $users[0]->remember_token = $request->remember_token;
        $users[0]->estatususuario = $request->estatususuario;
        $users[0]->recomendado_por_user_id = $request->recomendado_por_user_id;
        $users[0]->tipousuario_idtipousuario = $request->tipousuario_idtipousuario;
        $users[0]->verification_token = $request->verification_token;
        $users[0]->online = $request->online;
        
        if($users[0]->save()){
            $data       = $users;
            // Mail::to($data[0]->email)
            //         ->send(new emailRecPassSuccess());
        }else{
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar actualizar la informació de user";
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

    public function login()
    {

    }
    //metodo para consultar por username
    public function consUsername($username,$password,$control){
        error_log('===============consUsername====================');
        $code   =   "OK";
        $message=   "";
        $data   =   [];

        $user = User::select(
            'id',
            'username',
            'password',
            'remember_token',
            'email',
            'estatususuario',
            'tipousuario_idtipousuario',
            'verification_token',
            'online',
            'created_at',
            'updated_at'
        )
        ->where('username','=',$username)
        ->where('password','=',$password)
        ->get();

        
        if(count($user)!=0){
            if($user[0]->created_at != $user[0]->updated_at){
                $user[0]->online=$control;
                $user[0]->save();
            }
        
            if(isset($user[0]->username)){
                    // comentado por Carlos
                    /*Mail::to($user[0]->email)
                            ->send(new emailLogin());*/
            }
        }else{
            $code = "OK";
            $message = "No hubo resultados en la consula";
            $user = 0;
        }

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $user
        ],200);

    }

    //Metodo para logout
    public function logout($username,$control){
        $code   =   "OK";
        $message=   "";
        $data   =   [];

        $user = User::select(
            'id',
            'username',
            'password',
            'remember_token',
            'email',
            'estatususuario',
            'tipousuario_idtipousuario',
            'verification_token',
            'online'
        )
        ->where('username','=',$username)
        ->get();

        if(count($user)!=0){
            $user[0]->online=$control;
            $user[0]->save();
        }else{
            $code = "OK";
            $message = "No hubo resultados en la consula";
            $data = 0;
        }

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $user
        ],200);

    }


    //Método de consulta por username para la recuperación de contraseña
    public function recPass($username){
        $code       = "OK";
        $message    = "";
        $data       = [];

        $user = User::select(
            'id',
            'email',
            'estatususuario',
            'verification_token'
        )
        ->where('username','=',$username)->get();

        if(isset($user[0]->id)){

            // Mail::to($user[0]->email)
            //         ->send(new emailRecPass());
        }

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $user
        ],200);        
    }

    //metodo cambiar password
    public function cambPass($passs,$user){
        //dd($passs.'----'.$user);
        $code       = "OK";
        $message    = "";

        if(
        $user = User::where('username',$user)
            ->update(['password'=>$passs])
        ){

            return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success'
            ],200);  

        }else{

            return response()->json([
            'code'  => 'NOT OK',
            'msg'   => 'Error'
            ],200); 

        }


    }


    //metodo para notificación o email para la contraparte en el proceso de tracking
    public function notCP($id_user,$idd){
        $code       = "OK";
        $message    = "";
        $data       = [];

        if( is_numeric($id_user))
        {
            $user = User::find($id_user);
            if($user->online == 0){
                //Notificación por email
                // Mail::to($user->email)
                //     ->send(new emailTracking());
            }else{
                //Notificación simple
                
            }
        }
        else
        {
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }    

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $user
        ],200);
    }

    public function banks($id_user,$id_bank)
    {
        $banks_user = DB::table('users_bancos_pais_monedas as ubpm')
            ->join('bancos_pais_monedas as bpm', 'bpm.idbancos_pais_monedas', '=', 'ubpm.idbancos_pais_monedas')
            ->join('bancos', 'bpm.idbanco', '=', 'bancos.idbancos')
            ->join('monedas as mon', 'bpm.idmoneda', '=', 'mon.idmonedas')
            ->join('pais', 'bpm.idpais', '=', 'pais.idpais')
            ->select('ubpm.idusers_bancos_pais_monedas', 'bancos.nombre as nombre_banco', 'mon.nombremoneda as moneda','pais.nombre as pais')
            ->where('ubpm.iduser', '=', $id_user)
            ->where('mon.idmonedas', '=', $id_bank)
            ->orderBy('nombre_banco', 'asc')
            ->get();

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $banks_user
        ],200);
    }

    public function log_por_usuario_y_postura($user_id, $postura_match_id)
    {
        $log_data = [];

        if( is_numeric($user_id) && is_numeric($postura_match_id))
        {

            if($user_id != 0 && $postura_match_id != 0)
                $log_data = log_user::select('id','accion','created_at')->where('user_id','=',$user_id)->where('posturas_match_id','=',$postura_match_id)->orderBy('id', 'desc')->get();
            else if($user_id != 0 && $postura_match_id == 0)
                $log_data = log_user::where('user_id','=',$user_id)->orderBy('id', 'desc')->get();
            else if($user_id == 0 && $postura_match_id != 0)
                $log_data = log_user::where('posturas_match_id','=',$postura_match_id)->orderBy('id', 'desc')->get();
        }
        return $log_data;
    }

    public function negociaciones($id_user)
    {
        $posturas_matches = DB::table('posturas_matches as p_match')
                ->join('posturas as post', 'p_match.posturas_idposturas', '=', 'post.idposturas')
                ->select('p_match.*')
                ->where('p_match.users_idusers', '=', $id_user)
                ->orWhere('p_match.iduser2', '=', $id_user)
                ->orderBy('p_match.idposturasMatch','desc')
                // ->where('p_match.estatusOperaciones_idestatusOperacion','>=', 3)
                ->get();

        foreach ($posturas_matches as $postura_match)
        {
            $postura_match->estatus = self::get_status_postura($postura_match);

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

        return response()->json([
            'code'  => 'OK',
            'msg'   => 'Success',
            'data'  => $posturas_matches
        ],200);
    }

    private function get_status_postura($postura_match)
    {
        $status = "";
        $id_estatus_operacion = (int) $postura_match->estatusOperaciones_idestatusOperacion;
        $acepta_user_propietario = (int) $postura_match->acepta_user_propietario;
        $acepta_user_contraparte = (int) $postura_match->acepta_user_contraparte;


        if(($id_estatus_operacion == 1) && ($acepta_user_propietario == 1) && ( $acepta_user_contraparte == 1))
        {
            $status = "En negociación";
        }
        else if(($id_estatus_operacion == 1) && (( $acepta_user_propietario == 1) || ( $acepta_user_contraparte == 1)))
        {
            $status = "Match por aceptar";
        }
        else
        {
            $status = estatusOperacion::select('estatus')->where('idestatusOperacion','=',$id_estatus_operacion)->get()[0]->estatus;
        }

        return $status;
    }
}
