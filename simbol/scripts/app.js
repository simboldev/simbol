 'use strict';
 var mainApp = angular.module("app", ["ngRoute","ngResource",'mgcrea.ngStrap','ngCookies','cgNotify']);
 mainApp.controller('appController', function($scope,$http,$cookieStore,$sce,$window,$location,$routeParams,notify,$interval) {
    $scope.tittle_page = "Simbol";
    $scope.url_server = "http://localhost:8000";
    $scope.base_href = '/simbol-web/simbol/#!';

    // $scope.url_server = "http://108.174.197.107:8080";
    // $scope.base_href = '/simbol-web/simbol/#!';
    $scope.contNot=1;
    $scope.not=0;
    $scope.actCrono=null;
    
    // Recarga de index
    $scope.reload_page = function($url)
    {
		$window.location = $url;
		$window.location.reload();
    }

    $scope.index_init = function()
    {
    	$scope.web_safari = $scope.validar_navegador() == 2 ? true : false;
    }

    //Método para capturar url
    $scope.captUrl = function(){
       console.log(window.location.href+" url");
       console.log("/operacion/operacion/"+$routeParams.id_posturas_match);
       console.log("location "+$location.url());
	   if($location.url()=="/operacion/operacion/"+$routeParams.id_posturas_match){
		    	$cookieStore.put("actCrono",1);
		    	$scope.actCrono = $cookieStore.get("actCrono");
          console.log("actCrono "+$scope.actCrono);              
		    	var tiempoActual = new Date();
		    	$http({method:'GET',url: $scope.url_server+'/posturasMatch/'+$routeParams.id_posturas_match})
		    	.then(function(data){
		    		console.log("cronometro db "+data['data']['data'][0].cronometro);
		    		var cronometroDB = new Date(data['data']['data'][0].cronometro);
		    		console.log('tiempo actual '+tiempoActual+' y tiempo db '+cronometroDB);
		    		var diff  = tiempoActual - cronometroDB;
		    		console.log("el resultado es "+diff);
		    		var diffSeconds = diff/1000;
		    		var MM = Math.floor(diffSeconds/60);
		    		console.log("el resultado es "+MM);
		    		countdown(MM);
		    	}
		    	,function(xhr, ajaxOptions, thrownError){
            		console.log("POSTCONT:: Error al traer cronometro de posturasMatch"+xhr.status+" "+thrownError);
        		});

	   }else{
		    	console.log("no muestro cronometro");
		    	$scope.actCrono=$cookieStore.remove("actCrono");
	   }
    }

    $scope.verifChrono = function(){
    	var url = window.location.href;
    	if(url.indexOf("operacion/operacion") == -1){
    		$("#chrono").hide();
    	}
    }

    //Método que cronometra la consulta de notificaciones periodicamente inhabilitado por los momentos
  	$interval(function() {$scope.consNot();},4000);

    //Método para actualizar el estatus de la notificación a leida
    $scope.readNotify = function(idnotificaciones){
    	//console.log(idnotificaciones+" prueba");
    	$http({method: 'PUT',url: $scope.url_server+'/notificacion/'+idnotificaciones})
    	.then(function(data){
    		console.log("Modificación "+data['data']['data']);
    		if(data['data']['data']==1){
    			console.log(data['data']['msg']+" se modifico");
    			$scope.consultaNot();
    			//$location.url("/operacion/operacion/"+$routeParams.id_posturas_match);
    		}else{
    			console.log(data['data']['msg']);
    		}

    	},function(xhr, ajaxOptions, thrownError){
            console.log("POSTCONT:: Error al modificar la notificación"+xhr.status+" "+thrownError);
        });
    	//$scope.consNot();

        //$scope.consultaNot();

        $scope.consultaNot();

    }

    //Metodo para actualizar todas las notificaciones a leida cuando se ingrese a dicha interfaz
    $scope.update_all_not = function(){
    	console.log("severe severe"+$routeParams.id_posturas_match );
    	$http({method: 'PUT',url: $scope.url_server+'/notificacion/update_all_not/'+$scope.id})
		.then(function(data){
				console.log(data['data']['data'][0]);
		}
		,function (xhr, ajaxOptions, thrownError){ 
        		console.log("POSTCONT:: Error modificar el estatus de las notificaciones del usuario: \n Error: "+xhr.status+" "+thrownError);
      	});
    }

    //Método para sólo consulta de las notificaciones en su módulo
    $scope.consultaNot = function(){
   		
    	$http({method:'GET',url: $scope.url_server+'/notificaciones/consNotUserDate/'+$scope.id})
    	.then(function(data){
          if(data['data']['data']){
          	  console.log("el identificador es "+data['data']['data'][0].identificador)
              $scope.notificaciones = data['data']['data'];
          }else{
              console.log("La consulta no trajo datos de notificaciones por usuarios");
              $scope.notificaciones("Estimado Usuario hasta los momentos notificaciones");
          }
      }
      ,function (error){ 
				console.log("POSTCONT:: Error obteniendo data notificaciones_has_users: "+error)
		  });
    }

    //intervalo para notificaciones
    /*$interval(function()
	{
		console.log('====== Interval Notificaciones ======');		
		$scope.consNot()
	},10000);*/


    //Método para consultar si hay notificaciones pendientes por leer
    $scope.consNot = function(){
    	//console.log("usuario "+$scope.id);
    	$http({method:'GET',url: $scope.url_server+'/notificaciones/'+$scope.id+'/'+1})
    	.then(function(data){
    		console.log("la data es "+data['data']['data']);
    		if(data['data']['data']!=0){
           		console.log("entro al if");
	    		for(var i in data['data']['data']){
            		console.log("entro al for");        
	    			console.log("idnot= "+data['data']['data'][i].idnot);
	    			$scope.idNot = data['data']['data'][i].idnot;
	    			$http({method:'GET',url: $scope.url_server+'/notificaciones/'+$scope.idNot})
	    			.then(function(data){
	    				console.log("idnotificaciones "+data['data']['data'][0].idnotificaciones);
	    				console.log("cuerpo "+data['data']['data'][0].cuerpo);
	    				if(data['data']['data'][0].cuerpo){
                			console.log("por aqui");     
                		if($scope.contNot != $scope.not ){
                				$scope.not ++;
                				console.log($scope.not+" not activado");
                				$scope.notiPush(data['data']['data'][0].cuerpo);
                				console.log("contador not "+$scope.not);
                				$scope.contNot = $scope.not;
                		}                                              
	    					
	    				}else{
                			console.log("o por aqui");                                                                             
	    					console.log('La consulta no trajo datos de notificación')
	    				}
	    			}
	    			,function (error){ 
						console.log("POSTCONT:: Error obteniendo data notificaciones: "+error)
					});
	    		}
	    	}else{
          		$scope.not=0;
	    		console.log("La consulta no trajo datos de notificaciones por usuarios "+$scope.not);
	    	}
    		
    	}
    	,function (error){ 
				console.log("POSTCONT:: Error obteniendo data notificaciones_has_users: "+error)
		});
    }

    //Métodos para registrar de  notificaciones 
    $scope.regNotify=function(titulo,cuerpo,adjunto){
    	$scope.arrNotif = {
               'titulo':titulo,
               'cuerpo':cuerpo,
               'adjunto': adjunto,
               'notLeida':0
        }

        $http({method: 'POST',url: $scope.url_server+'/notificaciones',data:$scope.arrNotif })
        .then(function(data){
        	if(data['data']['data']){
        		console.log('exitoso');
        	}else{
        		console.log('no exitoso');
        	}
        }
        ,function(xhr, ajaxOptions, thrownError){
            console.log("POSTCONT:: Error al crear pivot de calificaciones y posturasmatch: \n Error: "+xhr.status+" "+thrownError);
        });
    }

    $scope.notiPush = function(sms){
    	notify(sms);
    }
    

    //metodo para autenticar
    $scope.login = function(user,password){
    	console.log('login')
    	console.log(user+" autenticación");
    	var control = 1;
    	$http({method:'GET',url: $scope.url_server+'/users/'+user+'/password/'+password+'/control/'+control})
		.then(function(data){
			console.log(data['data']['data']);
			if(data['data']['data'] == 0){
				console.log("autenticación - objeto vacio");
				$scope.mensaje = "¡¡Combinación Usuario y Password incorrecto, por favor ingrese nuevamente!!";
			}else{

				if(data['data']['data'][0].created_at == data['data']['data'][0].updated_at){
					
					//$scope.userCP = data['data']['data'][0].username;
					$("#userCP").val(data['data']['data'][0].username);
					$("#modalChangPass").modal('show');
				}else{
					
					console.log("autenticación - objeto lleno");
					$scope.user="";
					$scope.password="";
					console.log(data['data']['data'][0]);
					$cookieStore.put("id",data['data']['data'][0].id);
					$cookieStore.put("username",data['data']['data'][0].username);
					$cookieStore.put("email",data['data']['data'][0].email);
					$cookieStore.put("estatususuario",data['data']['data'][0].estatususuario);
					$cookieStore.put("recomendado_por_user_id",data['data']['data'][0].recomendado_por_user_id);
					$cookieStore.put("tipousuario_idtipousuario",data['data']['data'][0].tipousuario_idtipousuario);
					$cookieStore.put("verification_token",data['data']['data'][0].verification_token);
					$cookieStore.put("online",data['data']['data'][0].online);

					$scope.username = $cookieStore.get('username');
					$scope.reload_page($scope.base_href);
				}

				
			}
		},function (error){ 
				console.log("POSTCONT:: Error obteniendo data consUsername: "+error)
		});

    }

    $scope.logout = function(){
    	var control = 0;
    	$http({method:'GET',url: $scope.url_server+'/users/'+$scope.username+'/control/'+control})
    	.then(function(data){
    		console.log(data['data']['data']);
    		if(data['data']['data'] == 0){
				console.log("objeto vacio");
				$scope.mensaje = "¡¡Combinación Usuario y Password incorrecto, por favor ingrese nuevamente!!";
			}else{
				console.log("logout - objeto lleno");
				console.log(data['data']['data'][0]);
				console.log("cerrando sesión");
    			$cookieStore.remove('id');
    			$cookieStore.remove('username');
    			$cookieStore.remove('email');
    			$cookieStore.remove('estatususuario');
    			$cookieStore.remove('recomendado_por_user_id');
    			$cookieStore.remove('tipousuario_idtipousuario');
    			$cookieStore.remove('verification_token');
    			$cookieStore.remove('online');
    			
    			$scope.reload_page($scope.base_href);

			}
    	},function (error){ 
				console.log("POSTCONT:: Error obteniendo data consUsername: "+error)
		});
		
    }
   
   //Limpiador de cookies de sesión
   if($cookieStore.get('username')){

   		$scope.id = $cookieStore.get('id');
	    $scope.username = $cookieStore.get('username');
	    $scope.email = $cookieStore.get('email');
	    $scope.estatususuario = $cookieStore.get('estatususuario');
	    $scope.recomendado_por_user_id = $cookieStore.get('recomendado_por_user_id');
	    $scope.tipousuario_idtipousuario = $cookieStore.get('tipousuario_idtipousuario');
	    $scope.verification_token = $cookieStore.get('verification_token');
	    $scope.online = $cookieStore.get('online');

   }

   //metodo para redireccionar a la interfaz inicial luego de que una operación se haya cancelado por tiempo
   $scope.redirect = function(){
   		$scope.actCrono=$cookieStore.remove("actCrono");
   		console.log("valor de actCrono "+$cookieStore.remove("actCrono"));
   		$location.url("/postures/new");
   }

   //Método para redireccionar a la interfaz de calificación de usuario
		$scope.calificarInteface = function(){
			$scope.actCrono=$cookieStore.remove("actCrono");
			console.log("calificacion "+$routeParams.id_posturas_match);
			$location.url("/calificacion/calificacion/"+$routeParams.id_posturas_match);
		}

	//Método para redireccionar luego de calificar la contraparte
		$scope.calificacionExitosa = function(){
			console.log("id Pos$routeParams.id_posturas_match;tura es "+$routeParams.id_posturas_match);
			var idpm = $routeParams.id_posturas_match;
			

			//se consulta si la postura match ha sido cancelada
			var estatusOperaciones_idestatusOperacion = 0;
			$http({method: 'GET',url: $scope.url_server+'/posturasMatch/'+$routeParams.id_posturas_match})  
			.then(function(data){
				console.log("resultado de posturaMatch: "+data['data']['data'][0].estatusOperaciones_idestatusOperacion);
          		estatusOperaciones_idestatusOperacion = data['data']['data'][0].estatusOperaciones_idestatusOperacion;
			}
			,function (xhr, ajaxOptions, thrownError){ 
            			console.log("POSTCONT:: Error al traer los datos de la posturaMatch: \n Error: "+xhr.status+" "+thrownError);
      		});


			/*se consulta cuantas coincidencias de idPosturasMatch hay
			  en la tabla calificaciones si hay dos se hara el update*/

			$http({method: 'GET',url: $scope.url_server+'/calificaciones/califXidPmatch/'+$routeParams.id_posturas_match})  
			.then(function(data){
				console.log('valor de la respuesta de calificaciones '+data['data']['data']);
				if(data['data']['data']==true){
					console.log("id Postura es de nuevo es"+idpm);
					if(estatusOperaciones_idestatusOperacion != 4){
              			estatusOperaciones_idestatusOperacion = 3;
            		}
            		
					$http({method: 'PUT',url: $scope.url_server+'/posturas/cambiar_estatus_operacion/'+idpm+'/'+estatusOperaciones_idestatusOperacion})
					.then(function(data){
						console.log(data['data']['data'][0]);
						$scope.actCrono=$cookieStore.remove("actCrono");
						$location.url("/postures/new");
						
					}

					,function (xhr, ajaxOptions, thrownError){ 
            			console.log("POSTCONT:: Error modificar el estatus de operación de la postura: \n Error: "+xhr.status+" "+thrownError);
      				});
				}
			}
			,function (xhr, ajaxOptions, thrownError){ 
            	console.log("POSTCONT:: Error modificar el estatus de operación de la postura: \n Error: "+xhr.status+" "+thrownError);
      		});

			$location.url("/postures/new");
			setTimeout(function(){ location.reload(); }, 5000);
		}


	/*
		Se da formato a la fecha para ser almacenada en 
		base de datos.
		yyyy-MM-dd hh:mm = "2017-10-31 10:00"
	*/
	$scope.format_date_db = function(date,opt)
	{
		var new_date 		= ""
		var separador_fecha = "/";
		var separador_hora	= ":";
		var flag_fecha 		= true;
		var flag_hora 		= true;
		var meses = [	'enero','febrero','marzo','abril','mayo',
						'junio','julio','agosto','septiembre',
						'octubre','noviembre','diciembre'];
		
		switch(opt)
		{
			case 0: // Solo fecha con separador '/'
				flag_hora = false;
				break;
			case 1: // Solo hora con separador ':'
				flag_fecha = false;
				break;
			case 2:
				separador_fecha = "-";
				break;
			case 3:
				flag_fecha = true;
				flag_hora = true;
				separador_fecha = " ";
				break;
			case 4:
				flag_fecha = true;
				flag_hora = true;
				break;
			case 5:
				flag_fecha = true;
				flag_hora = false;
				separador_fecha = " ";
				break;
		}
		
		if(flag_fecha)
		{
			if (opt == 0 || opt == 1 || opt == 4)
			{
				// Formato dd/mm/YYYY
				new_date += ($scope.validar_navegador() == 2 ? (date.getDate()+1) : date.getDate())
				+ separador_fecha + (date.getMonth()+1)
				+ separador_fecha + date.getFullYear();
			}
			else if(opt == 2)
			{
				// Formato YYYY-mm-dd
				new_date += date.getFullYear()
				+ separador_fecha + (date.getMonth()+1)
				+ separador_fecha + ($scope.validar_navegador() == 2 ? (date.getDate()+1) : date.getDate());
			}
			else if(opt == 3 || opt == 5)
			{
				// Formato dd de month YYYY
				new_date += ($scope.validar_navegador() == 2 ? (date.getDate()+1) : date.getDate())
				+" de"+ separador_fecha + meses[date.getMonth()]
				+ separador_fecha + date.getFullYear();
			}
		}

		if(flag_hora)
		{
			new_date += ((flag_fecha == true ) ? " " : "") 
			+ date.getHours() 
			+ separador_hora + date.getMinutes();
		}
		
		return new_date;
	}

	$scope.validar_navegador = function()
	{
		var navegador = -1;
		if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) 
		{
			// alert('Opera');
			navegador = 0;
		}
		else if(navigator.userAgent.indexOf("Chrome") != -1 )
		{
			// alert('Chrome');
			navegador = 1;
		}
		else if(navigator.userAgent.indexOf("Safari") != -1)
		{
			// alert('Safari');
			navegador = 2
		}
		else if(navigator.userAgent.indexOf("Firefox") != -1 ) 
		{
			// alert('Firefox');
			navegador = 3
		}
		else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) //IF IE > 10
		{
			// alert('IE');
			navegador = 4;
		}  
		else 
		{
			// alert('unknown');
		}

		return navegador;
	}

	//Método de cargar objetos y valores al cargar la sección
	$scope.cargaPrimaria = function(){
		console.log("cargaPrimaria - se carga valores para el inicio de esta sección");
			$scope.boton = 0;
			$scope.botonR = 1;
	}

	//Metodo de recuperación de contraseña
	$scope.recuperarPassword = function(){
		console.log("en espera");
		console.log($scope.userR);
		if(angular.isUndefined($scope.userR)){
			$scope.mensaje="Por favor ingrese un username..";
		}else{
			$http({	method:'GET',
					url: $scope.url_server+'/users/'+$scope.userR
			})
			.then(function(data){
				console.log(data['data']['data']);
				if(data['data']['data'] == ""){
					console.log("objeto vacio");
					$scope.mensaje="El username introducido no existe, por favor introduzca un username válido...";
				}else{
					$scope.mensaje="Mensaje enviado a tu correo electrónico...";
				}
			},function(error) {
				console.log("POSTCONT:: Error obteniendo data recPass: "+error)
			});
			
		}
	}

	//Método para validar que el número de carácteres sea 8
	$scope.valNumCarac = function(){
		console.log('=-----------------valNumCarac-----------------------------');
		if($scope.passwordR.lenght <= 8){
			console.log($scope.passwordR);
			$scope.valnumcarPass="Correcto...";
		}else{
			console.log($scope.passwordR);
			$scope.valnumcarPass="Introduzca 8 ó más carácteres...";
		}
	}

	//Método de autenticación
	$scope.autenticar= function(){
		console.log("autenticación");
		//console.log(" el usuario es "+$scope.user);
		//console.log(" el password es "+$scope.password);
		var control = 1;
		$http({	method:'GET',
				url: $scope.url_server+'/users/'+$scope.user+'/password/'+$scope.password+'/control/'+control
		})
		.then(function(data){
			console.log(data['data']['data']);
			if(data['data']['data'] == ""){
				console.log("autenticación - objeto vacio");
				$scope.mensaje = "¡¡Combinación Usuario y Password incorrecto, por favor ingrese nuevamente!!"
			}else{
				console.log("autenticación - objeto lleno");
				$window.location="index1.html";
			}
		},function (error){ 
				console.log("POSTCONT:: Error obteniendo data consUsername: "+error)
		});

	}

	//Método que activa el botón iniciar sesión una vez validado que los campos de textos contengan caracteres
	$scope.activaBoton = function (){
		var pass = document.getElementById("password").value;
		
		
		if(pass.length >= 8 ){

			if(angular.isUndefined($scope.user) && angular.isUndefined($scope.password)){

				$scope.boton = 1;

			}else if(!angular.isUndefined($scope.user) && !angular.isUndefined($scope.password)){

				$scope.boton = 2;

			}else if(!angular.isUndefined($scope.user) && angular.isUndefined($scope.password)){

				$scope.boton = 3;

			}else if(angular.isUndefined($scope.user) && !angular.isUndefined($scope.password)){

				$scope.boton = 4;

			}

		}else if(pass.length <= 7){
			console.log("cont "+pass.length);
			$scope.boton = 1;
		}

	}

	$scope.desenfoqueBoton = function(){
			
			if(angular.isUndefined($scope.user) && angular.isUndefined($scope.password)){
				console.log("user y password nulos");
				$scope.boton = 1;

			}else if(!angular.isUndefined($scope.user) && !angular.isUndefined($scope.password)){
				console.log("user y password validos");
				
				$scope.boton = 2;
			
			}else if(!angular.isUndefined($scope.user) && angular.isUndefined($scope.password)){
				console.log("user valido y password nulo");
				$scope.boton = 3;

			}else if(angular.isUndefined($scope.user) && !angular.isUndefined($scope.password)){
				console.log("user nulo y password valido");
				$scope.boton =4;

			}
	}

	$scope.get_indicadores_de_mercado = function()
	{
		$http({	method: 'GET',
				url: $scope.url_server+'/posturasMatch/indicadores_de_mercado'})
			.then(function (data)
			{
				console.log('========= indicadores_de_mercado =========');
				$scope.indicadores = data['data']['data'];
				if($scope.validar_navegador() == 2) // Safari
				{
					$scope.indicadores.tasa.fecha = $scope.format_date_db(new Date(data['data']['data']['tasa']['fecha'].split(' ')[0]),5);
				}
				else
				{
					$scope.indicadores.tasa.fecha = $scope.format_date_db(new Date(data['data']['data']['tasa']['fecha']),5);
					// $scope.indicadores.tasa.fecha = $scope.format_date_db(new Date(data['data']['data']['tasa']['fecha']),3);
				}
			}
			,function(error) {
				console.log("POSTCONT:: Error obteniendo data indicadores de mercado: "+error);
			});
	}

	$interval(function()
	{
		console.log('====== Interval APP ======');		
		$scope.get_indicadores_de_mercado()
	},4000); //60000
 })
.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
    $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
}])
.config(function($timepickerProvider) {
  angular.extend($timepickerProvider.defaults, {
    timeFormat: 'h:mm a',
    length: 5,
    minuteStep: 10
  });
})
.config(function($datepickerProvider) {
  angular.extend($datepickerProvider.defaults, {
    dateFormat: 'dd/MM/yyyy',
    modelDateFormat: 'yyyy-MM-dd h:mm',
    startWeek: 1,
    timezone: 'UTC'
  });
})
.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}])
.config(['$routeProvider',function($routeProvider){
	$routeProvider
	.when('/amigos/amigoscomun/:id/contraparte/:id_postura/match/:id_posturas_match',{
		templateUrl: 'templates/amigos/amigoscomun.html',
		controller: 'MatchesCtrl'
	})
	.when('/recPass/recpass',{
		templateUrl: 'templates/recPass/recpass.html',
		controller: 'LoginCtrl'
	})
	.when('/',{
		templateUrl: 'templates/postures/new.html',
		controller: 'PostureCtrl'
	})
	.when('/postures/new',{
		templateUrl: 'templates/postures/new.html',
		controller: 'PostureCtrl'
	})
	.when('/operacion/operacion/:id_posturas_match',{
		templateUrl: 'templates/operacion/operacion.php',
		controller: 'ChatCtrl'
	})
	.when('/postures/:id/match/:id_postura_contraparte',{
		templateUrl: 'templates/postures/match.html',
		controller: 'MatchesCtrl'
	})
	.when('/postures/:id',{
		templateUrl: 'templates/postures/view.html',
		controller: 'MatchesCtrl'
	})
	.when('/postures/:id/matches',{
		templateUrl: 'templates/postures/matches.html',
		controller: 'MatchesCtrl'
	})
	.when('/calificacion/calificacion/:id_posturas_match',{
		templateUrl: 'templates/calificacion/calificacion.html',
		controller: 'PostureCtrl'
	})
	.when('/notificaciones/notificaciones',{
		templateUrl: 'templates/notificaciones/notificaciones.html',
		controller: 'appController'
	})
	.when('/detalle_negociacion/:id_postura_match',{
		templateUrl: 'templates/postures/detalle_negociacion.html',
		controller: 'MatchesCtrl'
	})
	.otherwise({
		redirectTo: '/'
	});
}])
	.controller('ChatCtrl',['$scope','envChat','$sce','$http',"$interval",'$cookieStore','$routeParams','$location',function($scope,envChat,$sce,$http,$interval,$cookieStore,$routeParams,$location){

      	$scope.paramPost = $routeParams.id_posturas_match;

	    /*Paginación de las notificaciones*/
	    $scope.post_current_page = 0;
	    $scope.post_page_size = 100;
	    $scope.post_pages = [];

	    $scope.selectBanco = {
	    	negBanco:null,
	    }

	    $scope.selectNacionalidad = {
	    	negNacionalidad: null,
	    }


	 /*METODO PARA EL ADMINISTRADOR CONFIRMAR TRANSFERENCIA*/
	 $scope.autorizaTransf = function(){

	 	
	 	$http({method: 'GET',url: $scope.url_server+'/negociacion/'+$scope.paramPost})
          .then(function (data){
          	for(var i in data['data']['data']){
          		
          		if(i == 0){
          			$scope.iduser1 = data['data']['data'][i].iduser;
          		}else{
          			$scope.iduser2 = data['data']['data'][i].iduser;
          		}
          		
          	}
          		console.log("valores usersssssss "+$scope.iduser1+"--"+$scope.iduser2);

          		$scope.envChat = {
					transferi:1,
					metransfirieron:0,
					conformetransfiere:0,
					conformetransferido:0,
					idp: $scope.paramPost,
					iduser: $scope.iduser1,
					iduser2:$scope.iduser2,
					opsatisf1:1,
					opsatisf2:''
				};

				$http({method: 'GET',url: $scope.url_server+'/negociacion/confirmacion1/'+$scope.iduser1+'/'+$scope.paramPost})
          			.then(function (data){
          				if(data['data']['data'] == 1){
          					console.log("sera "+data['msg']['msg']);
          					//$location.url("/operacion/operacion/"+$scope.paramPost);
          					
          				}else{
          					console.log("no sera "+data['msg']['msg']);

          				}
          		});

				$http({method: 'POST',url: $scope.url_server+'/tracking',data:$scope.envChat})
				.then(function(data){
					if(data['data']['data']){
						console.log('se realizó el 1er proceso tracking con exito')
						angular.copy({},$scope.envChat);
						$scope.settings.success = "Mensaje enviado";
						setTimeout(function(){ location.reload(); }, 1000);
					}			
				})
				.catch(function(err){angular.noop}); 
				
			
        });

	 }


	 $scope.confTransf2 = function(){

	 	$http({method: 'GET',url: $scope.url_server+'/negociacion/'+$scope.paramPost})
          .then(function (data){
			          for(var i in data['data']['data']){
			          		
			          		if(i == 0){
			          			$scope.iduser1 = data['data']['data'][i].iduser;
			          		}else{
			          			$scope.iduser2 = data['data']['data'][i].iduser;
			          		}
			          		
			          }

		          	  console.log("valores usersssssss "+$scope.iduser1+"--"+$scope.iduser2);
		          	  var idSave = ''
			          if($scope.id != $scope.iduser1){
			          		idSave = $scope.iduser1;
			          }else{
			          		idSave = $scope.iduser2;
			          }

			          console.log("la contraparte es "+idSave);
			          $scope.envChat = {
										transferi:1,
										metransfirieron:0,
										conformetransfiere:1,
										conformetransferido:0,
										idp: $scope.paramPost,
										iduser:idSave,
										iduser2:$scope.iduser1,
										opsatisf1:1,
										opsatisf2:1
					  };


					  $http({method: 'GET',url: $scope.url_server+'/negociacion/confirmacion2/'+$scope.iduser1+'/'+$scope.paramPost})
		          			.then(function (data){
		          				if(data['data']['data'] == 1){
		          					console.log("sera "+data['msg']['msg']);
		          					//$location.url("/operacion/operacion/"+$scope.paramPost);
		          					
		          				}else{
		          					console.log("no sera "+data['msg']['msg']);

		          				}
		          		});

		          	$http({method: 'PUT',url: $scope.url_server+'/tracking/'+$scope.envChat.idp+'/'+1})   
              		.then(function (data){
				        if(data['data']['data']){
                   			$scope.notificarTrack($cookieStore.get("userContrapp"));
                   			setTimeout(function(){ location.reload(); }, 1000);
                		}                                                                                   
		            }
              		,function(error) {
						console.log("POSTCONT:: Error obteniendo data2 users: "+error)
					});
								

        });

	 }


	 $scope.autorizaTransf2 = function(){

	 	
	 	$http({method: 'GET',url: $scope.url_server+'/negociacion/'+$scope.paramPost})
          .then(function (data){
          	for(var i in data['data']['data']){
          		
          		if(i == 0){
          			$scope.iduser1 = data['data']['data'][i].iduser;
          		}else{
          			$scope.iduser2 = data['data']['data'][i].iduser;
          		}
          		
          	}
          		console.log("valores usersssssss "+$scope.iduser1+"--"+$scope.iduser2);

          		$scope.envChat = {
						transferi:1,
						metransfirieron:1,
						conformetransfiere:1,
						conformetransferido:0,
						idp: $scope.paramPost,
						iduser:$scope.iduser1,
						iduser2:$scope.iduser2,
						opsatisf1:1,
						opsatisf2:1
				};

				$http({method: 'GET',url: $scope.url_server+'/negociacion/confirmacion3/'+$scope.iduser2+'/'+$scope.paramPost})
          			.then(function (data){
          				if(data['data']['data'] == 1){
          					console.log("sera "+data['msg']['msg']);
          					//$location.url("/operacion/operacion/"+$scope.paramPost);
          					
          				}else{
          					console.log("no sera "+data['msg']['msg']);

          				}
          		});

				$http({method: 'PUT',url: $scope.url_server+'/tracking/'+$scope.envChat.idp+'/'+2})   
              	.then(function (data){
				        if(data['data']['data']){
                   			$scope.notificarTrack($cookieStore.get("userContrapp"));
                   			setTimeout(function(){ location.reload(); }, 1000);
                		}                                                                                   
		        }
              ,function(error) {
					console.log("POSTCONT:: Error obteniendo data2 users: "+error)
			   });
				
        });

	 }


	 $scope.confTransf3 = function(){

	 	$http({method: 'GET',url: $scope.url_server+'/negociacion/'+$scope.paramPost})
          .then(function (data){
			          for(var i in data['data']['data']){
			          		
			          		if(i == 0){
			          			$scope.iduser1 = data['data']['data'][i].iduser;
			          		}else{
			          			$scope.iduser2 = data['data']['data'][i].iduser;
			          		}
			          		
			          }

		          	  console.log("valores usersssssss "+$scope.iduser1+"--"+$scope.iduser2);
		          	  var idSave = ''
			          if($scope.id != $scope.iduser1){
			          		idSave = $scope.iduser1;
			          }else{
			          		idSave = $scope.iduser2;
			          }

			          console.log("la contraparte es "+idSave);
			          $scope.envChat = {
								transferi:1,
								metransfirieron:1,
								conformetransfiere:1,
								conformetransferido:1,
								idp: $scope.paramPost,
								iduser: $scope.id,
								iduser2:idSave,
								opsatisf1:1,
								opsatisf2:1
					};


					  $http({method: 'GET',url: $scope.url_server+'/negociacion/confirmacion4/'+$scope.id+'/'+$scope.paramPost})
		          			.then(function (data){
		          				if(data['data']['data'] == 1){
		          					console.log("sera "+data['msg']['msg']);
		          					//$location.url("/operacion/operacion/"+$scope.paramPost);
		          					
		          				}else{
		          					console.log("no sera "+data['msg']['msg']);

		          				}
		          		});

		          	$http({method: 'PUT',url: $scope.url_server+'/tracking/'+$scope.envChat.idp+'/'+3})   
              		.then(function (data){
				        if(data['data']['data']){
                   			$scope.notificarTrack($cookieStore.get("userContrapp"));
                   			setTimeout(function(){ location.reload(); }, 1000);
                		}                                                                                   
		          	}
              		,function(error) {
						console.log("POSTCONT:: Error obteniendo data2 users: "+error)
					});  		
              		
        });

	 }



	/*METODO PARA VISUALIZAR NEGOCIACIONES*/
    $scope.verNegociacion = function(){
        console.log('postura Match match'+$scope.paramPost);


        $http({method: 'GET',url: $scope.url_server+'/negociacion/'+$scope.paramPost})
          .then(function (data){

          	var link = $scope.url_server+'/simbol-web/simbolbackend/storage/app/';
			var arrlink = link.split(":8000",2);
			link = arrlink[0]+arrlink[1];
			

            for(var i in data['data']['data']){
                console.log("iter is "+data['data']['data'][i].comprobante);
                
                  if(data['data']['data'][i].comprobante == null){
                      $scope.banconegBS = data['data']['data'][i].banco;
                      $scope.nrocuetanegBS = data['data']['data'][i].nrocuenta;
                      $scope.emailnegBS = data['data']['data'][i].email;
                      $scope.nroidentificacionnegBS = data['data']['data'][i].nroidentificacion;
                      $scope.comprobantenegBS = data['data']['data'][i].comprobante;
                      $scope.estatusnegBS = data['data']['data'][i].estatusnegociacion;
                      $scope.iduserBS = data['data']['data'][i].iduser;
                      $scope.nombrebancoBS = data['data']['data'][i].nombrebanco;



                  }else{

                      $scope.banconegDL = data['data']['data'][i].banco;
                      $scope.nrocuetanegDL = data['data']['data'][i].nrocuenta;
                      $scope.emailnegDL = data['data']['data'][i].email;
                      $scope.nroidentificacionnegDL = data['data']['data'][i].nroidentificacion;
                      $scope.comprobantenegDL = data['data']['data'][i].comprobante;
                      $scope.estatusnegDL = data['data']['data'][i].estatusnegociacion;
                      $scope.iduserDL = data['data']['data'][i].iduser;
                      $scope.nombrebancoDL = data['data']['data'][i].nombrebanco;

                  }

                  if(data['data']['data'][i].comprobante != null){

                  		if(i == 0){
                  			$scope.banconegBS = data['data']['data'][i].banco;
		                    $scope.nrocuetanegBS = data['data']['data'][i].nrocuenta;
		                    $scope.emailnegBS = data['data']['data'][i].email;
		                    $scope.nroidentificacionnegBS = data['data']['data'][i].nroidentificacion;
		                    $scope.comprobantenegBS = data['data']['data'][i].comprobante;
		                    $scope.estatusnegBS = data['data']['data'][i].estatusnegociacion;
		                    $scope.iduserBS = data['data']['data'][i].iduser;
		                    $scope.nombrebancoBS = data['data']['data'][i].nombrebanco;
                  		}else{
                  			$scope.banconegDL = data['data']['data'][i].banco;
                      		$scope.nrocuetanegDL = data['data']['data'][i].nrocuenta;
                      		$scope.emailnegDL = data['data']['data'][i].email;
                      		$scope.nroidentificacionnegDL = data['data']['data'][i].nroidentificacion;
                      		$scope.comprobantenegDL = data['data']['data'][i].comprobante;
                      		$scope.estatusnegDL = data['data']['data'][i].estatusnegociacion;
                      		$scope.iduserDL = data['data']['data'][i].iduser;
                      		$scope.nombrebancoDL = data['data']['data'][i].nombrebanco;
                  		}

                  }


                  	$scope.linkDL = link+$scope.comprobantenegDL;
             		$scope.linkBS = link+$scope.comprobantenegBS;
             		console.log("dime "+$scope.estatusnegBS+'----'+$scope.estatusnegDL);

             }
             
             
          },
          function(error){
              console.log("POSTCONT:: Error en la consulta de estatus de  negociacion: "+error)
          });

        
    }

	    $scope.guardarNegociacion = function(){

				console.log("abadat"+$scope.aba);
		    	$http({method: 'GET',url: $scope.url_server+'/negociacion/saveNegociacion/'+
		    		$scope.selectBanco.negBanco+'/'+
		    		$scope.aba+'/'+
		    		$scope.nrocuenta+'/'+
		    		$scope.email+'/'+
		    		$scope.selectNacionalidad.negNacionalidad+'/'+
		    		$scope.nroidentificacion+'/'+
		    		$scope.paramPost+'/'+
		    		$scope.id
		    	})
		    	.then(function (data){
		    		console.log("data guardada "+data['data']['data']['estatusNeg']);
		    		if(data['data']['data']['estatusNeg'] == 1){
		    			console.log('vamos para alla '+$location.url("/operacion/operacion/"+$scope.paramPost));
		    			console.log("tipo de valor es "+typeof($scope.paramPost));
		    			//$location.url("/operacion/operacion/"+$scope.paramPost);
		    			//$location.url();
		    			//$route.reload();
		    			$window.location.reload();
		    		}else if(data['data']['data']['estatusNeg'] == 2){
		    			console.log('cayo en 2');
		    			//$location.url("/operacion/operacion/"+$scope.paramPost);
		    			//$location.url();
		    			//$route.reload();
		    			$window.location.reload();
		    		}
		    		$scope.consEstatusNeg();
		    	}
				,function (xhr, ajaxOptions, thrownError){ 
					console.log("POSTCONT:: Error guardando negociacion: \n Error: "+xhr.status+" "+thrownError);
				});

	    }


	    $scope.consEstatusNeg = function(){
	    	console.log("si va "+$scope.selectBanco.negBanco+'--'+$scope.abadat+"--"+$scope.nrocuenta+"--"+$scope.email+"--"+$scope.selectNacionalidad.negNacionalidad+"--"+$scope.nroidentificacion);
	    	
	    	var link = $scope.url_server+'/simbol-web/simbolbackend/storage/app/';
			var arrlink = link.split(":8000",2);
			link = arrlink[0]+arrlink[1];

        	$http({method: 'GET',url: $scope.url_server+'/negociacion/consultNeg/'+$scope.paramPost+'/'+$scope.id})
	    	.then(function (data){

	    		console.log('buenooo  '+data['data']['data']['estatusNeg']+'--'+data['data']['data']['iduser']+'--'+data['data']['data']['moneda']);
	    		
	    		$scope.idNeg	=	data['data']['data']['idNeg'];
	    		$scope.estatusNeg = data['data']['data']['estatusNeg'];
	    		$scope.userNeg = data['data']['data']['iduser'];
	    		$scope.moneda = data['data']['data']['moneda'];
	    		$scope.banco = data['data']['data']['banco'];
	    		$scope.nrocuenta = data['data']['data']['nrocuenta'];
	    		$scope.email = data['data']['data']['email'];
	    		$scope.nroidentificacion = data['data']['data']['nroidentificacion'];
	    		console.log("comprobante "+data['data']['data']['comprobante']);
	    		$scope.comprobante = link+data['data']['data']['comprobante'];
	    	},
	    	function(error){
        		console.log("POSTCONT:: Error en la consulta de estatus de  negociacion: "+error)
      		});


	    }

	    $scope.cargaSelectBanco = function(){
	    	console.log("el parametro es "+$scope.paramPost)
	    	$http({method: 'GET',url: $scope.url_server+'/bancos'})
	    	.then(function (data){
	    		//console.log('okokok '+data['data']['data'][0].nombre);
	    		//for(var i in data['data'])
	    		$scope.selectBancos = data['data']['data'];
	    	},
	    	function(error){
        		console.log("POSTCONT:: Error obteniendo data bancos: "+error)
      		});

	    }
     
      	$scope.setPage = function(index, type_table) {
	        switch(type_table)
	        {
	            case 0: // Tabla mis posturas
	                $scope.post_current_page = index - 1;
	                break;
	            case 1: // Tabla mis negociaciones
	                $scope.talks_current_page = index - 1;
	                break;
	        }
      	};

      	/*Paginación de las notificaciones*/


      	//Mètodo que verifica el estatus de la operaciòn cada 2 segundos
	  	$scope.estatusOperacion = function(){
	  		$interval(function() {$scope.conStatusOper();},5000);
	  	}

      	//consulta el estatus del match de la postura
      	$scope.conStatusOper = function(){
      		console.log('la consulta esss '+$scope.paramPost);
      		console.log("urlurl "+window.location.href);
      		var param = window.location.href;
      		param = param.split("/operacion/operacion/",2);
      		param = param[1];
      		console.log("urlurl "+param);


      		$http({method: 'GET',url: $scope.url_server+'/posturasMatch/'+param})
      		.then(function (data){
      			console.log("datos estatusPostura "+data['data']['data'][0].estatusOperaciones_idestatusOperacion);
      			if(data['data']['data'][0].estatusOperaciones_idestatusOperacion == 3){
      				$scope.actCrono=$cookieStore.remove("actCrono");
      				//$('#myModaIInfOper').modal('show');
      			}else if(data['data']['data'][0].estatusOperaciones_idestatusOperacion == 4){
      				$scope.actCrono=$cookieStore.remove("actCrono");
      				$('#myModaIInfOperCan').modal('show');
      			}else if(data['data']['data'][0].estatusOperaciones_idestatusOperacion == 5){
      				$scope.actCrono=$cookieStore.remove("actCrono");
      				$('#myModaIInfOperDen').modal('show');
      			}

      		}
      		,function(error){
        		console.log("POSTCONT:: Error obteniendo data users: "+error)
      		});

      	}


		//intervalo de tiempo en el que se carga el tracking
		$scope.refTrack = function(){

			$scope.captUrl();

			$http({method: 'GET',url: $scope.url_server+'/posturasMatch/'+$routeParams.id_posturas_match})
			.then(function (data){
				if(data['data']['data'] == ""){
					console.log("POSTCONT:: la consulta no trajo datos posturasMatch: ");
				} else {

					console.log('usuario id sesion: '+$scope.id);
					console.log('usuario users_idusers '+data['data']['data'][0].users_idusers);
					console.log('usuario iduser2 '+data['data']['data'][0].iduser2);

					/*se crea esta variable para que en la interfaz del tracking se pueda usar para subir
					  las evidencias*/
					$scope.idUserContraparte = data['data']['data'][0].iduser2;

					if($scope.id == data['data']['data'][0].users_idusers){
						console.log("es la sesion");
						$cookieStore.put("userSes",data['data']['data'][0].users_idusers);
						$cookieStore.put("userContrapp",data['data']['data'][0].iduser2);
						$scope.param1 = $cookieStore.get("userSes");
						$scope.param2 = $cookieStore.get("userContrapp");
						
						if($scope.param1){
							$scope.usernameT = $scope.username;
						}

						if($scope.param2){
							console.log("param 2"+$scope.param2);
							//$http({method: 'GET',url: $scope.url_server+'/users/'+$scope.param2+'/'+$scope.param2})
							$http({method: 'GET',url: $scope.url_server+'/users/'+$scope.param2})
							.then(function (data){
								console.log(data['data']['data'].username+"data usuario2");
								if(data['data']['data'] == ""){
									console.log("POSTCONT:: la consulta no trajo datos de users: ");
								}else {
									$cookieStore.put('usernameContrap',data['data']['data'].username);
									$scope.usernameContrap = $cookieStore.get('usernameContrap');
								}
							}
							,function(error) {
								console.log("POSTCONT:: Error obteniendo data2 users: "+error)
							});
				 		}

					}else{
						console.log("es la contraporter");
						
						$cookieStore.put("userSes",data['data']['data'][0].iduser2);
						$cookieStore.put("userContrapp",data['data']['data'][0].users_idusers);
						$scope.param1 = $cookieStore.get("userContrapp");
						$scope.param2 = $cookieStore.get("userSes");
					
						if($scope.param1){
							console.log("param 1"+$scope.param1);
							//$http({method: 'GET',url: $scope.url_server+'/users/'+$scope.param1+'/'+$scope.param1})
							$http({method: 'GET',url: $scope.url_server+'/users/'+$scope.param1})
							.then(function (data){
								console.log(data['data']['data'].username+"data usuario");
								if(data['data']['data'] == ""){
									console.log("POSTCONT:: la consulta no trajo datos de users: ");
								}else {
									$cookieStore.put('userSes',data['data']['data'].username);
									$scope.usernameContrap = $cookieStore.get('userSes');
								}
							}
							,function(error) {
								console.log("POSTCONT:: Error obteniendo data1 users: "+error)
							}
							);
						}

						if($scope.param2){
							$scope.usernameT = $scope.username;
				 		}

					}				
					
					console.log("resultado de userContrapp: "+$cookieStore.get("userContrapp"));
				    console.log("resultado de userSes: "+$cookieStore.get("userSes"));
					console.log("POSTCONT:: datos posturasMatch: ");
					//console.log('parametro de url: '+$scope.param);
				}

			});
			
			if($location.url()=="/operacion/operacion/"+$routeParams.id_posturas_match){
				$interval(function() {  
					if($routeParams.id_posturas_match && $scope.id){
						$scope.myFunction();
						//$scope.mosConv($routeParams.id_posturas_match,$scope.id);
					}
				},4000);
			}
		}


		/*método para la primera consulta del tracking*/
		$scope.myFunction = function(){
			
			console.log("entro a myFunction "+$scope.tipousuario_idtipousuario);
			//vradio boton del contrato checkeado por default
			if($scope.tipousuario_idtipousuario != 5){
				$scope.contrato=$sce.trustAsHtml("<a href='' ng-click='downloadContr();' >T&eacute;rminos y Condiciones<a/>");
			}
			$scope.envChat = {
				iduser:$scope.id,
				username:$scope.username,
				idposturasMatch:$routeParams.id_posturas_match
			};
      		console.log("el idposturaMatch esssssss "+$scope.envChat.idposturasMatch)
        	$http({method:'GET',url: $scope.url_server+'/tracking/'+$routeParams.id_posturas_match})
		    .then(function(data){	
		    	//console.log('data es el valor '+data['data']['data'].idtracking);
				var c=0;
				if( data['data']['data'] != null){
					var c =	data['data']['data'].idtracking;
					var usernamePart = data['data']['data'].iduser;
					var usernamePart2 = data['data']['data'].iduser2;
          			console.log("valor del idtracking essssss "+usernamePart);
				}
				
				/*traemos un contraparte de la transaccion*/
				$http({method:'GET',url: $scope.url_server+'/users/'+usernamePart})
				.then(function(data){
					$scope.usernameP = data['data']['data'].username;
					console.log('el usuario es '+data['data']['data'].username);
				}
				,function(error) {
					console.log("POSTCONT:: Error obteniendo data users: "+error)
				});

				$http({method:'GET',url: $scope.url_server+'/users/'+usernamePart2})
				.then(function(data){
					$scope.usernameP2 = data['data']['data'].username;
					console.log('el usuario es '+data['data']['data'].username);
				}
				,function(error) {
					console.log("POSTCONT:: Error obteniendo data users: "+error)
				});


				console.log("C "+c);
				if( c == 0 ){
					console.log("c es 0");
					
						$scope.tr = 0;
						$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking0On.PNG' />");
						$scope.fase1 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf1' name='tranf1' data-toggle='modal' ng-model='tranf1' data-target='#myModal' disabled='disabled' />");
						$scope.fase2 = $sce.trustAsHtml("Conf. Transf. <input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal2' disabled='disabled'/>");
						$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal' disabled='disabled' />");
						$scope.fase4 = $sce.trustAsHtml("Conf. Transf. <input type='radio' aria-label='...' id='tranf4' name='tranf4' data-toggle='modal' ng-model='tranf4' data-target='#myModal2' disabled='disabled'/>");
					if($scope.tipousuario_idtipousuario != 5){	
						$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal6'><span class='Operacion-Satisfacto'>Rechazar</span></button>");
						$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In' >Operaci&oacute;n Satisfactoria</span></button>");
					}
				}else{
					console.log("c es diferente de 0");
					console.log("datos "+data['data']['data'].idtracking);
					for(var i in data['data']['data']){
						
							console.log("usuario de sesion base de datos "+data['data']['data'].iduser);

							if($scope.envChat.idposturasMatch == data['data']['data'].id){
								console.log("--idpostura validado "+data['data']['data'].iduser);
								console.log("usuario sesion "+$scope.id);
								//console.log("usuario contraparte "+$scope.param);

								if(data['data']['data'].iduser == $scope.id){
									console.log("usuario sesion");
                                                   
									if(data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==0  &&  data['data']['data'].metransfirieron==0 && data['data']['data'].conformetransferido == 0 ){
										console.log("1-0-0-0");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking1On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere<input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal2' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf4' name='tranf4' data-toggle='modal' ng-model='tranf4' data-target='#myModal2' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}else if (data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==1  && data['data']['data'].metransfirieron==0 && data['data']['data'].conformetransferido == 0 ){
										console.log("1-1-0-0");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking2On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere<input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal2' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;' >"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf4' name='tranf4' data-toggle='modal' ng-model='tranf4' data-target='#myModal2' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}else if (data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==1  &&  data['data']['data'].metransfirieron==1 && data['data']['data'].conformetransferido == 0 ){
										console.log("1-1-1-0");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking3On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere<input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal2' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf4' name='tranf4' data-toggle='modal' ng-model='tranf4' data-target='#myModal2' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}else if (data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==1  &&  data['data']['data'].metransfirieron==1 && data['data']['data'].conformetransferido == 1 ){
										console.log("1-1-1-1");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking4On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere<input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal2' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia <input type='radio' aria-label='...' id='tranf4' name='tranf4' data-toggle='modal' ng-model='tranf4' data-target='#myModal2' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											if(data['data']['data'].opsatisf1==1 && data['data']['data'].opsatisf1 == 1 ){
												$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											}else{
												$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='float: left;' ><span class='Cancelar-In' disabled='disabled' >Denunciar</span></button>");
											}
											
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='margin-left: 10px;' data-toggle='modal' data-target='#myModal5'><span class='Operacion-Satisfacto'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}


								}else{
									console.log("usuario contrapartesssss "+$scope.usernameContrap+'----'+$scope.usernameP);
									if(data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==0  &&  data['data']['data'].metransfirieron==0 && data['data']['data'].conformetransferido == 0 ){
										console.log("1-0-0-0");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking1On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere <input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia<input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal3' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia  <input type='radio' aria-label='...' id='tranf4' name='tranf4' ng-model='tranf4' data-toggle='modal' data-target='#myModal4' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}else if (data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==1  &&  data['data']['data'].metransfirieron==0 && data['data']['data'].conformetransferido == 0 ){
										console.log("1-1-0-0");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking2On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere <input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia<input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal3' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia  <input type='radio' aria-label='...' id='tranf4' name='tranf4' ng-model='tranf4' data-toggle='modal' data-target='disabled='disabled'#myModal4' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}else if (data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==1  &&  data['data']['data'].metransfirieron==1 && data['data']['data'].conformetransferido == 0 ){
										console.log("1-1-1-0");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking3On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere <input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia<input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal3' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' checked='true' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia  <input type='radio' aria-label='...' id='tranf4' name='tranf4' ng-model='tranf4' data-toggle='modal' data-target='#myModal4' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){
											$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}else if (data['data']['data'].transferi==1 &&  data['data']['data'].conformetransfiere==1  &&  data['data']['data'].metransfirieron==1 && data['data']['data'].conformetransferido == 1 ){
										console.log("1-1-1-1");
										
											$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking4On.PNG' />");
											$scope.fase1 = $sce.trustAsHtml("Transfiere <input type='radio' aria-label='...' id='tranf1' name='tranf1' ng-model='tranf1'data-toggle='modal' data-target='#myModal' checked='true' disabled='disabled'/><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
											$scope.fase2 = $sce.trustAsHtml("Confirm. Transferencia<input type='radio' aria-label='...' id='tranf3' name='tranf3' data-toggle='modal' ng-model='tranf3' data-target='#myModal3' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase3 = $sce.trustAsHtml("Transfiri&oacute; <input type='radio' aria-label='...' id='tranf2' name='tranf2' ng-model='tranf2' data-toggle='modal' data-target='#myModal2' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP+"</p>");
											$scope.fase4 = $sce.trustAsHtml("Confirm. Transferencia  <input type='radio' aria-label='...' id='tranf4' name='tranf4' ng-model='tranf4' data-toggle='modal' data-target='#myModal4' checked='true' disabled='disabled' /><p style='font-size: 11px;font-weight: bold;'>"+$scope.usernameP2+"</p>");
										if($scope.tipousuario_idtipousuario != 5){	
											if(data['data']['data'].opsatisf1==1 && data['data']['data'].opsatisf1 == 1 ){
												$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='float: left;' data-toggle='modal' data-target='#myModal7' ><span class='Operacion-Satisfacto'>Denunciar</span></button>");
											}else{
												$scope.botonCan = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='float: left;' ><span class='Cancelar-In' disabled='disabled'>Denunciar</span></button>");
											}
											$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='margin-left: 10px;' data-toggle='modal' data-target='#myModal5'><span class='Operacion-Satisfacto'>Operaci&oacute;n Satisfactoria</span></button>");
										}
									}

								}

							}

					}
					
				}
				
			})

		}


		//Mètodo para cancelar la operaciòn
		$scope.cancelarOperacion = function(){
			$scope.actCrono=$cookieStore.remove("actCrono");
			console.log("calificacion "+$routeParams.id_posturas_match);
			var estatusOperaciones_idestatusOperacion = 4;
			console.log("usuario: "+$scope.username+" y contraparte: "+$cookieStore.get('usernameContrap'));
            $http({method: 'PUT',url: $scope.url_server+'/posturas/cambiar_estatus_operacion/'+
                $routeParams.id_posturas_match+'/'+estatusOperaciones_idestatusOperacion})
            .then(function(data){
                console.log(data['data']['data'][0]);

        		$http({method: 'GET',url: $scope.url_server+'/operacion/cancelaOperation/'+$cookieStore.get("userContrapp")+'/'+$scope.username+'/'+$routeParams.id_posturas_match})
        		.then(function (data){
					console.log(data['data']['data']);
					if((data['data']['data']) != null){
						console.log("aqui "+$routeParams.id_posturas_match);
						$scope.actCrono=$cookieStore.remove("actCrono");;
						$location.url("/calificacion/calificacion/"+$routeParams.id_posturas_match);
					}

				}
				,function (xhr, ajaxOptions, thrownError){ 
					console.log("POSTCONT:: Error al guardar notificacion: \n Error: "+xhr.status+" "+thrownError);
				});

                //$location.url("/calificacion/calificacion/"+$routeParams.id_posturas_match);
            }
            ,function (xhr, ajaxOptions, thrownError){ 
                     console.log("POSTCONT:: Error modificar el estatus de operación de la postura: \n Error: "+xhr.status+" "+thrownError);
            });
			
		}


		//Método que consume api para la generación del contrato
		$scope.downloadContr = function(){

			$http({	method: 'GET',
					url: $scope.url_server+'/users/'+$scope.id
			})
			.then(function (data){
				if(data['data']['data'] == ""){
					console.log(data['data']['data']['online']);
					console.log("POSTCONT:: la consulta no trajo datos: ");
				} else {
					console.log(data['data']['data']['online']);
					console.log("POSTCONT:: datos para el pdf: ");
				}
			});

		}

		/*método para guardar las acciones del tracking*/
		$scope.submit = function()
		{
			/*recibo los valores de la vista */

			console.log("usuario "+$scope.id);
			console.log("usuario2 "+$cookieStore.get("userContrapp"));
			console.log(document.getElementById("tranf1").checked+"-----tranf1------");
			console.log(document.getElementById("tranf2").checked+"---tranf2--------");
			console.log(document.getElementById("tranf3").checked+"-----tranf3------");
			console.log(document.getElementById("tranf4").checked+"-----tranf4------");

			if(document.getElementById("tranf1").checked == true && document.getElementById("tranf2").checked == false && document.getElementById("tranf3").checked == false && document.getElementById("tranf4").checked == false ){
				console.log("entramos en la primera regla para guardar");
				$scope.envChat = {
					transferi:1,
					metransfirieron:0,
					conformetransfiere:0,
					conformetransferido:0,
					idp: $routeParams.id_posturas_match,
					iduser: $scope.id,
					iduser2:$cookieStore.get("userContrapp"),
					opsatisf1:1,
					opsatisf2:''
				};

				/*sentencia que consume la api rest para ejecutar la inserción de la data por el método post */
				//envChat.save($scope.envChat).$promise
				$http({method: 'POST',url: $scope.url_server+'/tracking',data:$scope.envChat})
				.then(function(data){
					if(data['data']['data']){
						console.log('se realizó el 1er proceso tracking con exito')
						angular.copy({},$scope.envChat);
						$scope.settings.success = "Mensaje enviado";
					}			
				})
				.catch(function(err){angular.noop});                                   
			
			  /*renderizamos el radio button e inhabilitamos su deselección*/  
              $scope.notificarTrack($cookieStore.get("userContrapp"));
              
              /*Actualizamos el estatus de la posturaMatch a procesando */                        
              var estatusOperaciones_idestatusOperacion = 2;
              $http({method: 'PUT',url: $scope.url_server+'/posturas/cambiar_estatus_operacion/'+
                $routeParams.id_posturas_match+'/'+estatusOperaciones_idestatusOperacion})
              .then(function(data){
                console.log(data['data']['data'][0]);
              }
              ,function (xhr, ajaxOptions, thrownError){ 
                      console.log("POSTCONT:: Error modificar el estatus de operación de la postura: \n Error: "+xhr.status+" "+thrownError);
              });

			}else if (document.getElementById("tranf1").checked==true && document.getElementById("tranf2").checked==false && document.getElementById("tranf3").checked==true && document.getElementById("tranf4").checked==false ){
						console.log("check 2");
						console.log("entramos en la segunda regla para guardar "+document.getElementById('idposturasMatch').value);
						$scope.envChat = {
								transferi:1,
								metransfirieron:0,
								conformetransfiere:1,
								conformetransferido:0,
								idp: $routeParams.id_posturas_match,
								iduser:$cookieStore.get("userContrapp"),
								iduser2:$scope.id,
								opsatisf1:1,
								opsatisf2:1
						};

						/*sentencia que consume la api rest para ejecutar la inserción de la data por el método post */
						/*envChat.update($scope.envChat).$promise
						.then(function(data){
							if(data.msg){
								angular.copy({},$scope.envChat);
								$scope.settings.success = "Mensaje enviado";
								
							}
						})
						.catch(function(err){angular.noop});*/
							/*renderizamos el radio button e inhabilitamos su deselección*/
              $http({method: 'PUT',url: $scope.url_server+'/tracking/'+$scope.envChat.idp+'/'+1})   
              .then(function (data){
				        if(data['data']['data']){
                   $scope.notificarTrack($cookieStore.get("userContrapp"));
                }                                                                                   
		          }
              ,function(error) {
								console.log("POSTCONT:: Error obteniendo data2 users: "+error)
							});                     
							
							//this.notif();
			}else if(document.getElementById("tranf1").checked==true && document.getElementById("tranf2").checked==true && document.getElementById("tranf3").checked==true && document.getElementById("tranf4").checked==false ){
					console.log($scope.id+" tres seleccionados");
					console.log("entramos en la tercera regla para guardar");

						$scope.envChat = {
								transferi:1,
								metransfirieron:1,
								conformetransfiere:1,
								conformetransferido:0,
								idp: $routeParams.id_posturas_match,
								iduser:$cookieStore.get("userContrapp"),
								iduser2:$scope.id,
								opsatisf1:1,
								opsatisf2:1
						};

						/*sentencia que consule la api para ejecutar la modificación de los datos por el método put */
						/*envChat.update({id:$scope.envChat.id},$scope.envChat).$promise
						.then(function(data){
							if(data.msg){
								$scope.settings.success = "Mensaje modificado";
							}
						})
						.catch(function(err){angular.noop});*/
						/*renderizamos el radio button e inhabilitamos su deselección*/
						$http({method: 'PUT',url: $scope.url_server+'/tracking/'+$scope.envChat.idp+'/'+2})   
              .then(function (data){
				        if(data['data']['data']){
                   $scope.notificarTrack($cookieStore.get("userContrapp"));
                }                                                                                   
		          }
              ,function(error) {
								console.log("POSTCONT:: Error obteniendo data2 users: "+error)
							});  
						//this.notif();
			}else if(document.getElementById("tranf1").checked==true && document.getElementById("tranf2").checked==true && document.getElementById("tranf3").checked==true && document.getElementById("tranf4").checked==true ){
				console.log("entramos en la tercera regla para guardar");

				$scope.envChat = {
								transferi:1,
								metransfirieron:1,
								conformetransfiere:1,
								conformetransferido:1,
								idp: $routeParams.id_posturas_match,
								iduser: $scope.id,
								iduser2:$cookieStore.get("userContrapp"),
								opsatisf1:1,
								opsatisf2:1
				};

				/*sentencia que consule la api para ejecutar la modificación de los datos por el método put */
				/*envChat.update({id:$scope.envChat.id},$scope.envChat).$promise
				.then(function(data){
					if(data.msg){
						$scope.settings.success = "Mensaje modificado";
					}
				})
				.catch(function(err){angular.noop});*/
				/*renderizamos el radio button e inhabilitamos su deselección*/
			  $http({method: 'PUT',url: $scope.url_server+'/tracking/'+$scope.envChat.idp+'/'+3})   
              .then(function (data){
				        if(data['data']['data']){
                   $scope.notificarTrack($cookieStore.get("userContrapp"));
                }                                                                                   
		          }
              ,function(error) {
								console.log("POSTCONT:: Error obteniendo data2 users: "+error)
				});  
				//this.notif();
			}
	
		}


		 /*envio de notificación tracking*/
		/*$scope.notif = function(){
			$notify('Notificación','Esto es un ejemplo de notificación');
		}*/


		/*función que deselecciona el radio button transferi en caso de cancelar en el modal de 
		confirmación*/
		$scope.deseleccionarTransferi=function(){
			envChat.get({idposturasMatch:$scope.envChat.idposturasMatch},function(data){
				/*objeto que obtendrá la vista para presentar los datos de la tabla tracking*/
				//compruebo si el objeto viene definido para manejar la deselección
				if(!data.tracking){
					console.log("dimensiono el objeto");
					data.tracking =0;
				}

				if(data.tracking.length >0){
					if(data.tracking[0].transferi==1){
						document.getElementById('tranf1').checked=true;
					}else{
						document.getElementById('tranf1').checked=false;
					}
				}else{
						document.getElementById('tranf1').checked=false;
				}
				

			});
		}

		/*función que deselecciona el radio button me transfirieron en caso de cancelar en el modal de 
		confirmación*/
		$scope.deseleccionarMeTransfirieron=function(){
			envChat.get({idposturasMatch:$scope.envChat.idposturasMatch},function(data){
				/*objeto que obtendrá la vista para presentar los datos de la tabla tracking*/
				//compruebo si el objeto viene definido para manejar la deselección
				if(!data.tracking){
					console.log("dimensiono el objeto");
					data.tracking =0;
				}

				if(data.tracking.length >0){
					if(data.tracking[0].metransfirieron==1){
						document.getElementById('tranf2').checked=true;
					}else{
						document.getElementById('tranf2').checked=false;
					}
				}else{
					document.getElementById('tranf2').checked=false;
				}
			});
		}

		$scope.deseleccionarConformeTransfiere=function(){
			console.log('deselecciono');
			envChat.get({idposturasMatch:$scope.envChat.idposturasMatch},function(data){
				/*objeto que obtendrá la vista para presentar los datos de la tabla tracking*/
				//compruebo si el objeto viene definido para manejar la deselección
				if(!data.tracking){
					console.log("dimensiono el objeto");
					data.tracking =0;
				}

				if(data.tracking.length >0){
					if(data.tracking[0].conformetransfiere==1){
						document.getElementById('tranf3').checked=true;
					}else{
						document.getElementById('tranf3').checked=false;
					}
				}else{
					document.getElementById('tranf3').checked=false;
				}
			});
		}

		$scope.deseleccionarConformeMeTransfirieron=function(){
			console.log('deselecciono');
			envChat.get({idposturasMatch:$scope.envChat.idposturasMatch},function(data){
				/*objeto que obtendrá la vista para presentar los datos de la tabla tracking*/
				//compruebo si el objeto viene definido para manejar la deselección
				if(!data.tracking){
					console.log("dimensiono el objeto");
					data.tracking =0;
				}

				if(data.tracking.length >0){
					if(data.tracking[0].conformetransferido==1){
						document.getElementById('tranf4').checked=true;
					}else{
						document.getElementById('tranf4').checked=false;
					}
				}else{
					document.getElementById('tranf4').checked=false;
				
}
			});
		}

		//Método de inicio para notificar via email los cambios del tracking
		$scope.notificarTrack = function(v){
			$scope.notCP(v);
		}

		$scope.compTracking = function(v){
			console.log("transferi 1 me transfirieron 1");
			$scope.trackImage=$sce.trustAsHtml("<img src='./images/png/tracking2On.PNG' />");
			
			if(document.getElementById("contrato").checked==true){
				$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Operacion-s' style='margin-left: 10px;' ><span class='Operacion-Satisfacto' >Operación Satisfactoria</span></button>");
			}else{
				$scope.botonOpeSat = $sce.trustAsHtml("<button class='Rectangle-Cancelar-In' style='margin-left: 10px;' disabled='disabled' ><span class='Cancelar-In'>Operación Satisfactoria</span></button>");
			}

			$scope.notCP(v);
		}

		//metodo para consultar si la contraparte está online y notificar en el tracking
		$scope.notCP = function(v){
			console.log("valor para notCP "+v);
			$http({method: 'GET',url: $scope.url_server+'/users/'+v+'/notCP/'+v})
			.then(function (data){
				if(data['data']['data']['online'] == 0){
					console.log(data['data']['data']['online']);
					console.log("POSTCONT:: envio de correo  a la contraparte: ");
				} else {
					console.log(data['data']['data']['online']);
					console.log("POSTCONT:: envio de notificación  a la contraparte: ");
				}
				
			}
			,function (error){ 
				console.log("POSTCONT:: Error obteniendo data notCP: "+error);
			});

		}

		//metodo que envia mensaje de chat
		$scope.sendChat = function(){

			var iduser = document.getElementById("iduser").value;
	        var username = document.getElementById("username").value;  
	        var textouser = document.getElementById("textouser").value; 
	        var adjuntouser = '';
        	var posturasMatches_idposturasMatch = $routeParams.id_posturas_match; 

        	var data = {
        		'iduser' : iduser,
        		'username' : username,
        		'textouser' : textouser,
        		'adjuntouser' : adjuntouser,
        		'posturasMatches_idposturasMatch' : posturasMatches_idposturasMatch

        	};

        	$http({method: 'POST',url: $scope.url_server+'/chat',data:data})
        	.then(function (data){
				console.log(data['data']['message']+" despues de salvar ");
				console.log(data['data']['textouser']+" el texto ha sido");
				//$scope.mosConv(document.getElementById("posturasMatch_idposturasMatch").value,document.getElementById("iduser").value);
			}
			,function (xhr, ajaxOptions, thrownError){ 
				console.log("POSTCONT:: Error al enviar chat: \n Error: "+xhr.status+" "+thrownError);
			});

			document.getElementById("textouser").value="";
			
		}


		//mostrar conversación
		$scope.mosConv=function(idPostura,idUser){
			console.log("iduser: "+idUser+" y idpostumat"+idPostura);

			//repositorio de archivos para mostrar en la conversación
			var link = $scope.url_server+'/simbol-web/simbolbackend/storage/app/';
			var arrlink = link.split(":8080",2);
			link = arrlink[0]+arrlink[1];
			console.log("ruta para adjuntar "+link);
			//var link ='http://52.170.252.66/simbolbackend/storage/app/';
			//var link ='http://localhost/simbolbackend/storage/app/';
			//variable que guardará la imagen de icono de archivo adjunto
			var icon="";
			var html="";

			$http({method: 'GET',url: $scope.url_server+'/chat/'+idPostura})
			.then(function (data){

				for(var i in data['data']['data']){
					console.log(data['data']['data'][i]['textouser'] + ' iterar');

					//validación del icono a mostrar por la conversación
					/*if( data['data']['ext'][i] == "jpg" ){
						console.log("es imagen jpg");
						icon="<img src='images/png/jpg.png' />";
					}else if ( data['data']['ext'][i] == "png" ){
						console.log("es imagen png");
						icon="<img src='images/png/png.png' />";
					}else if ( data['data']['ext'][i] == "pdf" ){
						console.log("es imagen pdf");
						icon="<img src='images/png/pdf.png' />";
					}*/
					icon="<img src='images/jpg/archivo.jpg' />";

					
					if(data['data']['data'][i]['iduser'] == idUser){
							
							var hora = data['data']['data'][i]['created_at'];

							html += "<div class='mensaje-autor'>"+
								   "	<div class='yo2' >"+data['data']['data'][i]['username']+"</div>"+
								   "<div class='flecha-izquierda'></div>"+
								   " <div class='contenido'>";

									if(data['data']['data'][i]['textouser'] != null){
										html +=""+data['data']['data'][i]['textouser']+"<br>";
										html +="<div class='horaChat'>"+hora.substring(11,16)+"</div>";
									}


								   if(data['data']['data'][i]['adjuntouser'] != null){
								   		html +="<a href='"+link+data['data']['data'][i]['adjuntouser']+"' download="+data['data']['data'][i]['adjuntouser']+"  target='_blank' >"+icon+"</a>";
								   }
								   html +=" </div>"+
								   " <div class='flecha-derecha'></div>"+
								   "</div>";
							//html.scrollTop = html.scrollHeight;
							$scope.conv=$sce.trustAsHtml(html);

					} else {
						//la contraparte
						var hora = data['data']['data'][i]['created_at'];
						html += "<div class='mensaje-amigo'>"+
								   "	<div class='yo' >"+data['data']['data'][i]['username']+"</div>"+
								   "<div class='flecha-izquierda'></div>"+
								   " <div class='contenido'>";

								   if(data['data']['data'][i]['textouser'] != null){
										html +=""+data['data']['data'][i]['textouser']+"<br>";
										html +="<div class='horaChat'>"+hora.substring(11,16)+"</div>";
									}

								   if(data['data']['data'][i]['adjuntouser'] != null){
								   		html +="<a href='"+link+data['data']['data'][i]['adjuntouser']+"' download="+data['data']['data'][i]['adjuntouser']+"  target='_blank' >"+icon+"</a>";
								   }
								   html +=" </div>"+
								   " <div class='flecha-derecha'></div>"+
								   "</div>";
						//html.scrollTop = html.scrollHeight;
						$scope.conv=$sce.trustAsHtml(html);

					}
					
					
				}
				
				
			}
			,function (error){ 
				console.log("POSTCONT:: Error obteniendo data chat: "+error);
			});

		}

		//redirección a nueva postura
		$scope.redirecNewPostura = function(){
			$scope.actCrono=$cookieStore.remove("actCrono");
			$location.url("/postures/new");
			setTimeout(function(){ location.reload(); }, 5000);
		}

		
	}])
	.factory('envChat',function($resource){
		return $resource("http://52.170.252.66:8080/tracking/:id",
		//return $resource("http://localhost:8000/tracking/:id",
			{id:"@_idposturasMatch"},
			{update: {method:"PUT",params:{id:"@id"}}
		})
	});
