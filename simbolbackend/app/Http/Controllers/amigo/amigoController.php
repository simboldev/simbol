<?php

namespace App\Http\Controllers\amigo;

use DB;
use App\User;
use App\amigo;
use App\notificacion;
use Illuminate\Http\Request;
use App\notificaciones_has_users;
use App\Http\Controllers\Controller;

class amigoController extends Controller
{
    private $code       = '';
    private $message    = '';
    private $data       = [];
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

        $amigo = new \App\amigo($request->all());
    
        if( $amigo->save())
        {
            $data   =   $amigo;

            $respNotif=$this->saveNotify($data->user1,$data->user2);
            if($respNotif == true){
                    $resp=true;
            }else{
                $code       = "NOTOK";
                $message    = "Ocurrio un problema al intentar guardar notificación";
            }

        }else{
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar registrar en la tabla amigos";
        }

        return response()->json([
            'code'      => $code,
            'msg'   => $message,
            'data'      => $data
        ],200);

    }

    //Método para guardar en la tabla notificaciones
    public function saveNotify($idusuarioC,$iduser){
      
        $usuario = User::select('username')->where('id','=',$idusuarioC)->get();
        
        $notificacion = new \App\notificacion();
        $not_has_user = new \App\notificaciones_has_users();

        $notificacion->titulo='Notificacion de Amistad';
        $notificacion->cuerpo='Estimado usuario se le notifica que el usuario '.$usuario[0]->username.' lo ha agragado a su circulo de confianza';
        $notificacion->adjunto='';
        $notificacion->notleida=0;
        $notificacion->status_notifications_id=1;

        if($notificacion->save()){
            $not_has_user->users_id = $iduser;
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

    //Método para saber si dos usuarios son amigos
    public function consAmistad($id1,$id2){
        $code       = "OK";
        $message    = "";
        $data       = [];

        if( is_numeric($id1) && is_numeric($id2)){
            $data = amigo::select(
                    'idamigos',
                    'user1',
                    'user2')
                    ->where([['user1','=',$id1],['user2','=',$id2],])
                    ->orWhere([['user2','=',$id1],['user1','=',$id2],])
                    ->get();
        }else{
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser valores numéricos";
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
    private function get_array_amigos_id($id_user)
    {
        $amigos = DB::table('amigos')
            ->where('user1', '=', $id_user)
            ->orWhere('user2', '=', $id_user)
            ->get();

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

    private function get_array_users($array_user_ids)
    {   
        $array_users = array();

        /*for ($i=0; $i< count($array_user_ids); $i++)
        {
            $user = User::select('id','username')->where('id','=',$array_user_ids[$i])->get()[0];
            array_push($array_users,$user);
        }*/

        foreach ($array_user_ids as $key => $value) {
            $user = User::select('id','username')->where('id','=',$value)->first();
            array_push($array_users,$user->id,$user->username);
        }

        return $array_users;
    }

    public function amigos_en_comun($id_user_1,$id_user_2)
    {
        
        $amigos_user_1 = self::get_array_amigos_id($id_user_1);
        $amigos_user_2 = self::get_array_amigos_id($id_user_2);

        $amigos_array = array();
        array_push($amigos_array,$amigos_user_1);
        array_push($amigos_array,$amigos_user_2);
        $amigos_array = array_intersect($amigos_user_1, $amigos_user_2);
      
        if(count($amigos_array) > 0)
            $amigos_array = self::get_array_users($amigos_array);
        
        $this->data = $amigos_array;
        
        return response()->json([
            'code'=> $this->code,
            'message' => $this->message,
            'data'=> $this->data
        ],
        200);
    }

    public function amigos_en_comun_tabla($id_user_1,$id_user_2)
    {

        $amigos_user_1 = self::get_array_amigos_id($id_user_1);
        $amigos_user_2 = self::get_array_amigos_id($id_user_2);

        $amigos_array = array();
        array_push($amigos_array,$amigos_user_1);
        array_push($amigos_array,$amigos_user_2);
        $amigos_array = array_intersect($amigos_user_1, $amigos_user_2);
      
        if(count($amigos_array) > 0)
            $amigos_array = self::get_array_users($amigos_array);
        
        $data[] = $amigos_array;

        if(sizeof($data)>=1){
            foreach ($data as $key => $value) {

                    $negConcretadas = DB::select('select idposturasMatch from posturas_matches 
                    where(users_idusers='.$value[$key].' or iduser2 ='.$value[$key].
                    ') and estatusOperaciones_idestatusOperacion = 4');

                    $negNoConcretadas = DB::select('select idposturasMatch from posturas_matches 
                        where(users_idusers='.$value[$key].' or iduser2 ='.$value[$key].
                        ') and estatusOperaciones_idestatusOperacion != 4');


                    $ultimaOperacion = DB::select("select max(DATE_FORMAT(updated_at,'%d/%m/%Y')) as 
                        ultimaOperacion from posturas_matches where 
                        users_idusers=".$value[$key]." or iduser2 =".$value[$key]);


                    $contVolumen = DB::select('select quiero from posturas where quiero_moneda_id = 2 and iduser = '.$value[$key].' group by quiero ');
                    $sumVolumen =DB::select('select SUM(quiero) as suma from posturas where quiero_moneda_id = 2 and iduser = '.$value[$key]);

                    if($contVolumen && $sumVolumen){
                        $volume = ( ($sumVolumen[0]->suma * 1) / count($contVolumen));
                        $volume = round($volume,2);
                    }else{
                       $volume = 0; 
                    }
                    

                    $contRanking = DB::select('select puntos from calificaciones where iduser = '.$value[$key].
                            ' and idusuariocalificado = '.$id_user_1);
                    $sumRanking = DB::select('select SUM(puntos) as sumP from calificaciones where iduser = '.$value[$key].' and idusuariocalificado = '.$id_user_1);

                    
                    if($contRanking){
                        $ranking = (($sumRanking[0]->sumP * 1)/count($contRanking));
                        $ranking = round($ranking);
                        $ranking = trim($ranking) * 1;
                    }else{
                        $ranking = 0;
                    }

                    $etiquetaStar = "<label for='star1' class='starLabel' >&#9733;</label>";
                    $etiq='';

                    for($i=0;$i<$ranking;$i++){
                        $etiq = $etiq.$etiquetaStar;
                    }

                    if($ranking > 5){
                        $ranking = '5+'.$etiq;
                    }else{
                        $ranking = $etiq;
                    }

                    $dat = array(
                        'iduser'=> $value[0],
                        'username' => $value[1],
                        'negConcretadas' => count($negConcretadas),
                        'negNoConcretadas' => count($negNoConcretadas),
                        'ultimaOperacion' => $ultimaOperacion[0]->ultimaOperacion,
                        'volume'    =>  $volume,
                        'ranking'   =>  $ranking,
                    );

                    array_push($this->data, $dat);
            }
            
        }else{
                    $this->message = "¡¡La busqueda no arrojo resultados!!";
        }
  
        return response()->json([
            'code'=> $this->code,
            'message' => $this->message,
            'data'=> $this->data
        ],
        200);
    }
}
