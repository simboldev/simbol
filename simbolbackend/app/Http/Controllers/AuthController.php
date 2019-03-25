<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  var $code       = '';
  var $message    = '';
  var $data       = [];

  /**
   * Create user
   *
   * @param  [string] username
   * @param  [string] email
   * @param  [string] password
   * @param  [string] password_confirmation
   * @param  [integer] idtipousuario
   * @return [string] json
   */
  public function signup(Request $request)
  {
    error_log('signup-=======================');
    $request->validate([
      'username'     => 'required|string',
      'email'    => 'required|string|email|unique:users',
      'password' => 'required|string|confirmed',
    ]);
    error_log('signup-======='.$request->name.'========='.$request->email.'======='.$request->password);

    $user = new User([
      'username'     => $request->name,
      'email'    => $request->email,
      'password' => bcrypt($request->password),
      'tipousuario_idtipousuario' => $request->idtipousuario,
    ]);

    if($user->save())
    {
      $this->msg = 'Usuario creado!';
    }
    else
    {
      $this->msg = 'No se ha podido crear el usuario :(';
    }
    return response()->json([
      'message' => $this->msg], 201);
  }

  public function user(Request $request)
  {
    return response()->json($request->user());
  }

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

  public function register (Request $request) {
    $validator = Validator::make($request->all(), [
      'username' => 'required|string|max:255',
      'nombres' => 'required|string|max:255',
      'apellidos' => 'required|string|max:255',
      'tipousuario_idtipousuario' => 'required|integer',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails())
    {
      return response(['errors'=>$validator->errors()->all()], 422);
    }

    $request['password']=Hash::make($request['password']);
    $user = User::create($request->toArray());

    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
    $response = ['token' => $token];

    return response()->json([
        'code'  => 'OK',
        'msg'   => 'Success',
        'data'  => $response
    ],200);
  }

  public function login (Request $request) {
    $code_msg = 'NOTOK';
    $msg = 'Error';
    $user = User::where('username',$request['username'])->first();
    if ($user) {
      if (Hash::check($request['password'], $user->password)) {
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['user' => $user, 'token' => $token];
        $code_msg = 'OK';
        $msg = 'Success';
        $code = 200;
      } else {
        $response = "Contraseña incorrecta, intenta nuevamente!!";;
        $code = 422;
      }
    } else {
      $response = 'Usuario no existe';
      $code = 422;
    }
    // return response($response, $code);
    return response()->json([
        'code'  => $code_msg,
        'msg'   => $msg,
        'data'  => $response
    ],$code);
  }

  public function logout (Request $request) {
    $token = $request->user()->token();
    $token->revoke();
    $response = 'Vuelve pronto';
    // return response($response, 200);
    return response()->json([
        'code'  => 'OK',
        'msg'   => 'Success',
        'data'  => $response
    ],200);
  }

  public function change_password (Request $request) {
    error_log($request);
    error_log('------------------username = '.$request['username']);
    $code_msg = 'NOTOK';
    $msg = 'Error';
    $user = User::where('username', $request['username'])->first();
    
    if ($user) {
      if($user->update(['password'=>Hash::make($request['password'])]))
      {
        $code_msg = 'OK';
        $msg    = "Success";
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['message' => 'Cambio de contraseña exitoso, puedes ingresar con tu nueva clave.',
                     'token' => $token];
        $code =  200;
      }
      else
      {
        $response = "Cambio de contraseña no exitoso, intenta nuevamente.";
        $code = 422;
      }
    } else {
      $response = 'Usuario no existe';
      $code = 422;
    }
    // return response($response, $code);
    return response()->json([
        'code'  => $code_msg,
        'msg'   => $msg,
        'data'  => $response
    ],$code);
  }
}
