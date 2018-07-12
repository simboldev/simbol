<?php

namespace App\Http\Controllers\chat;


use App\chat;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class chatController extends Controller
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
        $code       = "OK";
        $message    = "";
        $data       = [];

        //
        $chat = new \App\chat($request->all());
        $chat->textouser = Crypt::encrypt($chat->textouser);
        //preguntamos si existe un archivo
        if($request->hasFile('adjuntouser')){
            $chat->adjuntouser = $request->file('adjuntouser')->store('simbol');
        }

        if( $chat->save())
        {
            //$arrChat = self::get_chat($chat->posturasMatches_idposturasMatch);
            //$arrChat = $this->show($chat->posturasMatch_idposturasMatch);

            $code       = "OK";
            $message    = "success";
            
        }
        else
        {
            $code       = "NOTOK";
            $message    = "Ocurrio un problema al intentar guardar el mensaje del chat";         
        }
        
        return response()->json([
            'code'      => $code,
            'message'   => $message,
            
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
        $ext        = [];

        //$chats = chat::where('posturasMatch_idposturasMatch','=',$id)->get();
        if( is_numeric($id))
        {
            //$data = self::get_chat($id);
            $chats = chat::select('iduser','username','textouser','adjuntouser','posturasMatches_idposturasMatch','created_at')->where('posturasMatches_idposturasMatch','=',$id)->get();
            
            foreach ($chats as $i) {
                $i['textouser'] = Crypt::decrypt($i['textouser']);
                $data = $chats;
                if(strpos($i['adjuntouser'],".jpg") || strpos($i['adjuntouser'],".JPG") || strpos($i['adjuntouser'],".jpeg") || strpos($i['adjuntouser'],".JPEG")){
                        array_push($ext,"jpg");
                }else if(strpos($i['adjuntouser'],".png") || strpos($i['adjuntouser'],".PNG")){
                        array_push($ext,"png");
                }else if (strpos($i['adjuntouser'],".pdf") || strpos($i['adjuntouser'],".PDF")){
                        array_push($ext,"pdf");
                }
                
            }
            
        }
        else
        {
            $code = "NOTOK";
            $message = "Parámetro requerido (id) debe ser un valor numérico";
        }

        return response()->json([
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
            'ext'       => $ext
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

    private function get_chat($id)
    {
        $chats = chat::select('iduser','username','textouser','adjuntouser','posturasMatches_idposturasMatch')->where('posturasMatches_idposturasMatch','=',$id)->get();
        $c=0;
        foreach ($chats as $i) {
            $i->textouser = Crypt::decrypt($i->textouser);
            $chats[$c]->textouser=$i->textouser;
            $c++;
        }
        return $chats;
    }
}
