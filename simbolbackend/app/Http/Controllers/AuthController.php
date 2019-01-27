<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
// $user = new \App\user(['username'=> 'prueba1','email'=> 'p1@sc.co','password' => bcrypt('123123123')]);
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

  public function login(Request $request)
  {
    $request->validate([
        'email'       => 'required|string|email',
        'password'    => 'required|string',
        'remember_me' => 'boolean',
    ]);

    $credentials = request(['email', 'password']);
    if (!Auth::attempt($credentials)) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = $request->user();
    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->token;

    if ($request->remember_me) {
      $token->expires_at = Carbon::now()->addWeeks(1);
    }

    $token->save();

    return response()->json([
      'access_token' => $tokenResult->accessToken,
      'token_type'   => 'Bearer',
      'expires_at'   => Carbon::parse(
          $tokenResult->token->expires_at)
              ->toDateTimeString(),
    ]);
  }


  public function logout(Request $request)
  {
    $request->user()->token()->revoke();

    return response()->json(['message' => 
        'Successfully logged out']);
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
}