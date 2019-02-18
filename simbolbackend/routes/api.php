<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//112 rutas
Route::group(['prefix' => 'auth'], function ()
{
    Route::post('login', 'AuthController@login');
    // Route::post('signup', 'AuthController@signup');
  
    Route::group(['middleware' => 'auth:api'], function()
    {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::get('amigos/consAmistad/{id1}/{id2}', 'amigo\amigoController@consAmistad')->name('amigos.consAmistad');
Route::get('amigos/amigos_en_comun/{iduser_1}/{iduser_2}', 'amigo\amigoController@amigos_en_comun')->name('amigos.amigos_en_comun');
Route::get('amigos/amigos_en_comun_tabla/{iduser_1}/{iduser_2}', 'amigo\amigoController@amigos_en_comun_tabla')->name('amigos.amigos_en_comun_tabla');
Route::resource('amigos','amigo\amigoController');

Route::resource('bancos','banco\bancoController');
Route::get('bancos/consBancos/{postMatch}/{idUser}','banco\bancoController@consBancos');


Route::get('calificaciones/califXidPmatch/{idp}', 'calificaciones\calificacionesController@califXidPmatch')->name('calificaciones.califXidPmatch');
Route::put('calificaciones/savePostAndCalif/{id_posturas_match}/{id_calificaciones}',
'calificaciones\calificacionesController@savePostAndCalif')->name('calificaciones.calificacionesController');	
Route::resource('calificaciones','calificaciones\calificacionesController');

Route::resource('chat','chat\chatController');

Route::resource('confiabilidad','confiabilidad\confiabilidadController');

Route::resource('denuncias','denuncia\denunciaController');

Route::resource('estatusPosturas','estatusPostura\estatusPosturaController');

Route::resource('negociacion','negociacion\negociacionController');
Route::get('negociacion/consultNeg/{idPosturaMatch}/{iduser}', 'negociacion\negociacionController@consultNeg')->name('negociacion.consultNeg');

Route::get('negociacion/negociacion_por_postura_match/{idPosturaMatch}', 'negociacion\negociacionController@negociacion_por_postura_match')->name('negociacion.negociacion_por_postura_match');

Route::get('negociacion/saveNegociacion/{idbancoNeg}/{abaNeg}/{nrocuentaNeg}/{emailNeg}/{nacionalidadNeg}/{nroidentificacionNeg}/{idposturamatchNeg}/{iduser}/{iduser_contraparte}', 'negociacion\negociacionController@saveNegociacion')->name('negociacion.saveNegociacion');
Route::post('negociacion/saveComprobante', 'negociacion\negociacionController@saveComprobante')->name('negociacion.saveComprobante');
Route::post('negociacion/saveComprobanteContraparte', 'negociacion\negociacionController@saveComprobanteContraparte')->name('negociacion.saveComprobanteContraparte');

Route::get('negociacion/confirmacionTransferencia/{miIdNeg}/{idNegContraparte}/{estatus}', 'negociacion\negociacionController@confirmacionTransferencia')->name('negociacion.confirmacionTransferencia');
Route::get('negociacion/confirmacionTransferenciaBackoffice/{idNeg}/{idNegContraparte}/{estatus}', 'negociacion\negociacionController@confirmacionTransferenciaBackoffice')->name('negociacion.confirmacionTransferenciaBackoffice');
Route::get('negociacion/confirmacion1/{iduser}/{idPosturaMatch}', 'negociacion\negociacionController@confirmacion1')->name('negociacion.confirmacion1');
Route::get('negociacion/confirmacion2/{iduser}/{idPosturaMatch}', 'negociacion\negociacionController@confirmacion2')->name('negociacion.confirmacion2');
Route::get('negociacion/confirmacion3/{iduser}/{idPosturaMatch}', 'negociacion\negociacionController@confirmacion3')->name('negociacion.confirmacion3');
Route::get('negociacion/confirmacion4/{iduser}/{idPosturaMatch}', 'negociacion\negociacionController@confirmacion4')->name('negociacion.confirmacion4');


Route::resource('monedas','moneda\monedaController');

Route::put('notificacion/update_all_not/{iduser}',
'notificacion\notificacionController@update_all_not')->name('notificacion.update_all_not');
Route::put('notificacion/{idNot}',
'notificacion\notificacionController@modEstatusNot')->name('notificacion.notificacionController');
Route::get('notificaciones/consNotUserDate/{iduser}', 'notificacion\notificacionController@consNotUserDate')->name('notificacion.consNotUserDate');
Route::get('notificaciones/{iduser}/{v_p}', 'notificacion\notificacionController@consNotXuser')->name('notificacion.consNotXuser');
Route::resource('notificaciones','notificacion\notificacionController');

Route::get('operacion/cancelaOperation/{v_iduser}/{v_username}/{v_idPosturaMatch}','operacion\operacionController@cancelaOperation')->name('operacion.cancelaOperation');
Route::resource('operacion','operacion\operacionController');

Route::resource('paises','pais\paisController');


Route::get('posturas/{id}/{v_p}', 'postura\posturaController@posturasIndex')->name('posturas.posturasIndex');
Route::get('posturas/{id}/posturas_macth/{nro_registros}/{pagina}', 'postura\posturaController@posturas_macth')->name('posturas.posturas_macth');
Route::get('posturas/lista_posturas/{id_user}/{status_postura}/{order_by}/{nro_registros}/{pagina}/', 'postura\posturaController@lista_posturas')->name('posturas.lista_posturas');

Route::put('posturas/cambiar_estatus_postura/{id_postura}/{id_estatus}', 'postura\posturaController@cambiar_estatus_postura')->name('posturas.cambiar_estatus_postura');
Route::put('posturas/cambiar_estatus_operacion/{id_postura_match}/{id_estatus}', 'postura\posturaController@cambiar_estatus_operacion')->name('posturas.cambiar_estatus_operacion');
Route::resource('posturas','postura\posturaController');


Route::get('posturasMatch/montosXposturas/{id_p}/{id_user}', 'posturasMatch\posturasMatchController@montosXposturas')->name('posturasMatch.montosXposturas');
Route::get('posturasMatch/postura_match_por_posturas/{id_postura}/{id_postura_contraparte}', 'posturasMatch\posturasMatchController@postura_match_por_posturas')->name('posturasMatch.postura_match_por_posturas');
Route::get('posturasMatch/indicadores_de_mercado', 'posturasMatch\posturasMatchController@indicadores_de_mercado')->name('posturasMatch.indicadores_de_mercado');
Route::resource('posturasMatch','posturasMatch\posturasMatchController');

Route::resource('recomendaciones','recomendacion\recomendacionController');

Route::resource('tipousuarios','tipousuario\tipousuarioController');

// Route::get('users/{iduser}/{id_id}', 'user\userController@shows')->name('users.shows');
Route::post('users/login', 'user\userController@login')->name('users.login');
Route::get('users/{v_username}/control/{v_control}', 'user\userController@logout')->name('users.logout');
Route::get('users/{v_username}', 'user\userController@recPass')->name('users.recPass');
Route::get('users/{v_username}/password/{v_password}/control/{v_control}', 'user\userController@consUsername')->name('users.consUsername');
Route::get('users/{id}/notCP/{id_s}', 'user\userController@notCP')->name('users.notCP');
Route::get('users/{id}/banks/{id_bank}', 'user\userController@banks')->name('users.banks');
Route::get('users/{id}/negociaciones', 'user\userController@negociaciones')->name('users.negociaciones');
Route::get('users/cambPass/{pass}/{user}', 'user\userController@cambPass')->name('users.cambPass');
Route::resource('users','user\userController');


Route::put('tracking/modEstatusBoton/{idpm}/{idUser}', 'tracking\trackingController@modEstatusBoton')->name('tracking.modEstatusBoton');
Route::put('tracking/{idp}/{fase}', 'tracking\trackingController@mod')->name('tracking.mod');
Route::resource('tracking','tracking\trackingController');

Route::get('log_user/por_usuario_y_postura/{user_id}/{postura_match_id}', 'LogUserController@por_usuario_y_postura')->name('log_user.por_usuario_y_postura');
Route::resource('log_user','LogUserController');


Route::resource('posturas_rechazada','posturasRechazada\posturasRechazadaController');

Route::resource('log_user','LogUserController');

Route::resource('rate_range','rateRangeController');
