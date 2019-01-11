<?php

namespace App\Http\Controllers\banco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\banco;
use App\posturasMatches;
use App\postura;
use App\bancos_pais_monedas;
use App\posturas_has_users_bancos;


class bancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $code       = "OK";
        $message    = "Success";

        $data       =   banco::get();
       
        return response()->json([
            'code'=> $code,
            'message' => $message,
            'data'=> $data
        ],
        200);
    }


    public function consBancos($postMatch,$idUser){
        $code       = "OK";
        $message    = "Success";

        $bancosArray = [];

        $dataPosturaMatch = posturasMatches::where('idposturasMatch',$postMatch)
        ->first();
        

        $dataPostura = postura::where('idposturas',$dataPosturaMatch->posturas_idposturas)
        ->orWhere('idposturas',$dataPosturaMatch->postura_contraparte_id)
        ->where('iduser',$idUser)
        ->first();

        $datUsersBanc = posturas_has_users_bancos::where('posturas_id',$dataPostura->idposturas)->get();

        if($dataPostura->quiero_moneda_id == 1){
            //banco nacional
            foreach ($datUsersBanc as $key => $v) {
                $bancosArray[$key] = $v->users_bancos_pais_monedas_id;
            }

            $datBancosPaisMonedas = bancos_pais_monedas::select(
                'idbanco'
           )
           ->join('bancos','bancos.idbancos','bancos_pais_monedas.idbanco')
           ->where('idpais',1)
           ->whereIn('idbanco',array($bancosArray[0],$bancosArray[1]))
           ->first();
           $idbanco = $datBancosPaisMonedas->idbanco;

        }else{
            //banco extranjero
            foreach ($datUsersBanc as $key => $v) {
                $bancosArray[$key] = $v->users_bancos_pais_monedas_id;
            }

           $datBancosPaisMonedas = bancos_pais_monedas::select(
                'idbanco'
           )
           ->join('bancos','bancos.idbancos','bancos_pais_monedas.idbanco')
           ->where('idpais',2)
           ->whereIn('idbanco',array($bancosArray[0],$bancosArray[1]))
           ->first();
           $idbanco = $datBancosPaisMonedas->idbanco;

        }

        $datBanco = banco::where('idbancos',$idbanco)->first();

        $data = $datBanco;

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
