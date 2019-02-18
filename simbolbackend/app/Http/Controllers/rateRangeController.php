<?php

namespace App\Http\Controllers;

use DB;
use App\rate_range;
use Illuminate\Http\Request;

class rateRangeController extends Controller
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
      error_log('=============CREAR RATE RANGE======================='.$request);
      $data = [];
      $rate_range = $request->all();
      $rules = [ 'initial_amount' => 'required',
                 'initial_amount' => 'required'
               ];

      $validator = \Validator::make($rate_range, $rules);
      if ($validator->fails())
      {
        $code       = "NOTOK";
        $message    = $validator->errors()->all();
      }
      else
      {
        $rate_range = new \App\rate_range($rate_range);
        if( $rate_range->save())
        {
          $code       = "OK";
          $message    = "";
          $data       = $rate_range;
        }
        else
        {
          $code       = "NOTOK";
          $message    = "Ocurrio un problema al intentar crear el rango de la tasa";
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
      error_log('------------show RATE---------------------'.$id);
      $code = 'OK';
      $message = '';
      $data = [];
      
      if( is_numeric($id))
      {
          if($id == -1)
          {
            $data = DB::table('rate_ranges')->orderBy('id', 'desc')->first();
          }
          else
          {
            $data = DB::table('rate_ranges')->find($id);
          }
      }
      else
      {
        $code = "NOTOK";
        $message = "Parámetro requerido (id) debe ser un valor numérico";
      }

      return response()->json([
          'code'=> $code,
          'message' => $message,
          'data'=> $data
      ],
      200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rate_range  $rate_range
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate_range $rate_range)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate_range  $rate_range
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate_range $rate_range)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate_range  $rate_range
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate_range $rate_range)
    {
        //
    }
}
