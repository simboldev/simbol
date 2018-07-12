<?php

namespace App\Http\Controllers\posturasRechazada;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class posturasRechazadaController extends Controller
{
    var $code       = 'OK';
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
        $posturas_rechazadas = DB::table('posturas_rechazadas as post_rech')
            ->orderBy('post_rech.id', 'desc')->get();

        $this->data = $posturas_rechazadas;
            
        return response()->json([
            'code' => $this->code,
            'message' => "Success",
            'data'=> $this->data
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
        $data = [];
        $post_rech_data = $request->all();

        // Creamos las reglas de validación
        $rules = [
            'mi_postura_id'         => 'required',
            'postura_rechazada_id'  => 'required'
            ];

        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $validator = \Validator::make($post_rech_data, $rules);
        if ($validator->fails())
        {
            $this->code       = "NOTOK";
            $this->message    = $validator->errors()->all();
        }
        else
        {
            $post_rech = new \App\posturas_rechazada($post_rech_data);
            if( $post_rech->save())
            {
                $this->data       = $post_rech->id;
            }
            else
            {
                $this->code       = "NOTOK";
                $this->message    = "Ocurrio un problema al intentar guardar la información en posturas rechazadas";
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
