mainApp
.controller('MatchesCtrl',['$scope','$http', '$routeParams', '$location', '$cookieStore', '$interval','$timeout','$sce', function($scope,$http, $routeParams,$location,$cookieStore, $interval,$timeout,$sce){
	$scope.posture_user = {};
	$scope.postura_contraparte = {};
	$scope.postures_match = {};
	$scope.postura_match_det = {};
	$scope.amigos_en_comun = [];
	$scope.id = $routeParams.id;
	$scope.id_postura_contraparte = $routeParams.id_postura_contraparte;
	$scope.text_primera_negociacion = 'Este usuario no ha concretado una operación';
	$scope.text_ultima_negociacion = 'Este usuario no ha concretado una operación';
	$scope.idcontraparte=null;
	$scope.amigoscomun=[];

	// Monedas registradas para el intercambio en el sistema
	$http({ method: 'GET',
		url: $scope.url_server+'/monedas' })
		.then(function (data){
			$scope.monedas = data['data']['data'];
		    $scope.iwant_opt_s = $scope.monedas[0]['admin_simbolo'];
		    $scope.ihave_opt_s = $scope.monedas[1]['admin_simbolo'];
		}
		,function (error){ 
			console.log("POSTCONT:: Error obteniendo data monedas: "+error)
		});

	$scope.init_view = function()
	{
		if ($routeParams.id)
		{
			$scope.id = $routeParams.id;
			$scope.current_id = $routeParams.id;
			get_posture_user($routeParams.id);
		}
		else
		{
			$location.url('postures/new');
		}
	}

	$scope.init_matches = function()
	{
		if ($routeParams.id)
		{
			$scope.id = $routeParams.id;
			$scope.current_id = $routeParams.id;
			get_posture_user($routeParams.id);
			$scope.nro_posturas_consulta = 10;
			$scope.pagina_consulta = 1;
			$scope.get_list_posturas_matches($routeParams.id,$scope.nro_posturas_consulta,$scope.pagina_consulta);
		
			// Funcion que refresca la lista de matches con la postua actual
			$interval(function()
			{
				if($routeParams.id)
				{
					$scope.get_list_posturas_matches($routeParams.id,$scope.nro_posturas_consulta,$scope.pagina_consulta);
				}
			},4000);
		}
		else
		{
			$location.url('postures/new');
		}
	}

	// Se obtienen los match con la postura indicada
	$scope.get_list_posturas_matches = function($postura_id, $nro_posturas_consulta, $pagina_consulta)
	{
		$http({ method: 'GET',
				url: $scope.url_server+'/posturas/'+$routeParams.id+'/posturas_macth/'+$nro_posturas_consulta+'/'+$pagina_consulta })
				.then(function (data){
					$scope.postures_match = data['data']['data'];
				}
				,function (error){
					console.log("MATCHCONT:: Error obteniendo data posturas match: ");
					console.log(error);
				});
	}

	$scope.init_match = function()
	{
		if( ($routeParams.id) && ($routeParams.id_postura_contraparte))
		{
			$scope.id = $routeParams.id;
			$scope.current_id = $routeParams.id;
			$scope.text_primera_negociacion = 'Este usuario no ha concretado una operación';
			$scope.text_ultima_negociacion = 'Este usuario no ha concretado una operación';
			// $scope.posture_user = get_posture($routeParams.id);
			get_posture_user($routeParams.id);
			get_postura_contraparte($routeParams.id_postura_contraparte);
		}
	}
	$scope.delete_posture = function($idpostura)
	{
		$confirm = confirm("¿Eliminarás postura actual?");
		if($confirm == true)
		{
        	if (cambiar_estatus_postura($idpostura,6) == true)
        	{
        		$location.url('postures/new');
        		alert('La postura se ha eliminado exitosamente.');
        	}
        	else
        		alert('Ha ocurrido un problema, intenta eliminar la postura nuevamente.');
		}
	}

	$scope.rechazar_posture = function($id_mi_postura,$id_postura_rechazada,$opt)
	{
		$confirm = confirm("Confirma que deseas rechazar postura.");
		if($confirm == true)
		{
        	if($opt == 0) // Postura
        	{
        		if(cambiar_estatus_postura($id_postura_rechazada,1) == true){
	        		$current_location = $location.url().split('/')[3];
	        		
	        		$scope.enviar_rechazo_postura($id_mi_postura, $id_postura_rechazada);

	        		if( $current_location == 'matches')
	        			$("#card_posture_match_"+$id_postura_rechazada).hide();
	        		else
	        			$location.url('postures/'+$id_mi_postura+'/matches');
	        	}
	        	else
	        		alert('Ha ocurrido un problema intentalo nuevamete.');
        	}
        	else if($opt == 1) // Operacion
        		cambiar_estatus_operacion(1,4);	
		}
	}
	
	$scope.aceptar_posture = function($id_posture,$id_postura_contraparte,$id_user_contraparte)
	{
		// alert($id_posture+","+$id_postura_contraparte+","+$id_user_contraparte)
		var resp = false;
		$confirm = confirm("Confirma que deseas aceptar postura.");
		if($confirm == true)
		{
			// alert($id_posture+","+$cookieStore.get('id')+","+$id_postura_contraparte+","+$id_user_contraparte)
    		$scope.exist_posture($id_posture,$cookieStore.get('id'),$id_postura_contraparte,$id_user_contraparte);
		}
	}

	$scope.ver_postura_match = function($id_posture, $id_postura_contraparte)
	{
		$location.path('/postures/'+$id_posture+'/match/'+$id_postura_contraparte);
	}

	$scope.denunciar_posture = function($id_postura_match,$usuario_id)
	{
		alert('Seccion en desarrollo');
	}
	
	function get_amigos_en_comun($id_user_1,$id_user_2)
	{
		var amigos_en_comun = [];
		// Se obtienen amigos en comun
		$http({ method: 'GET',
				url: $scope.url_server+'/amigos/amigos_en_comun/'+$id_user_1+'/'+$id_user_2})
				.then(function (data){
					
					//amigos_en_comun = if(data['data']['data'].length > 0) ? data['data']['data']; : data['data']['data'][0];
					if(data['data']['data'] > 0){
						amigos_en_comun = data['data']['data'];
					}else{
						amigos_en_comun = data['data']['data'][0];
					}

					return amigos_en_comun;

				}
				,function (error){
					console.log("MATCHCONT:: Error obteniendo data amigos en comun:");
					console.log(error);
				});
		
	}

	$scope.enviar_rechazo_postura = function($id_mi_postura, $id_postura_rechazada)
	{
      	// Creo la postura rechazada
		$http({ method: 'POST',
				url: $scope.url_server+'/posturas_rechazada',
				data: {	'mi_postura_id'  		: $id_mi_postura,
		            	'postura_rechazada_id'	: $id_postura_rechazada
		            }
    		})
			.then(function (data){
				console.log('Postuara '+data['data']['data']+' rechazada con exito');
		        // alert('Postuara rechazada con exito');
			}
			,function (xhr, ajaxOptions, thrownError){ 
				console.log("MATCHES_CONT:: Error al reachazar postura: \n Error: "+xhr.status+" "+thrownError);
			});		
	}
	$scope.crear_postura_match = function($id_postura,$id_user_postura,$id_postura_contraparte,$id_user_postura_contraparte)
	{
		// CREO LA POSTURA
		// alert('creo postura')
		console.log("============crear_postura_match=================");
		console.log($id_postura+','+$id_user_postura+','+$id_postura_contraparte+','+$id_user_postura_contraparte);
        $scope.posture_match_save = {
            'posturas_idposturas'  		: $id_postura,
            'postura_contraparte_id'	: $id_postura_contraparte,
            'estatusMatch'   : 1,
            'users_idusers'  : $id_user_postura,
            'iduser2'        : $id_user_postura_contraparte,
            'cronometro'     : $scope.format_date_db(new Date(),2),
            'estatusOperaciones_idestatusOperacion'	: 1,
            'acepta_user_propietario' : true,
            // 'acepta_user_contraparte': true
        }

		console.log($scope.posture_match_save);

      	// Creo la postura match
		$http({ method: 'POST',
				url: $scope.url_server+'/posturasMatch',
				data: $scope.posture_match_save})
				.then(function (data){
					console.log(data['data']['data']);
			        // submit_button_disabled_act(1);
			        $scope.id_postura_match = parseInt(data['data']['data']);
			        if($scope.id_postura_match != 0)
		    		{
						cambiar_estatus_postura($id_postura,4);
						cambiar_estatus_postura($id_postura_contraparte,4);
						// Debo enviar al indez y notificar que debe esperar a que la contraparte acepte el match
						// $location.url('/operacion/'+$scope.id_postura_match);
						alert('Debes esperar a que la contraparte acepte el match');
						$location.url('postures/new');
		    		}
		    		else
		    		{
		    			alert('Match no efectivo, intentalo nuevamete');
		    		}
				}
				,function (xhr, ajaxOptions, thrownError){ 
					console.log("MATCHES_CONT:: Error al crear postura match: \n Error: "+xhr.status+" "+thrownError);
        			// submit_button_disabled_act(1);
				});
	}

	$scope.exist_posture = function($id_postura,$id_user_postura,$id_postura_contraparte,$id_user_postura_contraparte)
	{
		// Valido si existe la postura
		$http({ method: 'GET',
        url: $scope.url_server+'/posturasMatch/postura_match_por_posturas/'+$id_postura+'/'+$id_postura_contraparte})
        .then(function (data)
        {
			$scope.postura_existente = data['data']['data'];

			if( $scope.postura_existente.length == 0)
			{
				$scope.crear_postura_match($id_postura,$id_user_postura,$id_postura_contraparte,$id_user_postura_contraparte);
			}
			else
			{
	            // Actualizo la postura
	            if($scope.postura_existente[0]['estatusOperaciones_idestatusOperacion'] == 1)
	            {
	              	$scope.postura_existente[0]['acepta_user_contraparte'] = 1;

					$scope.post_match = {
						idposturasMatch: 						parseInt($scope.postura_existente[0]['idposturasMatch']),
						estatusMatch: 							parseInt($scope.postura_existente[0]['estatusMatch']),
						cronometro: 							$scope.postura_existente[0]['cronometro'],
						confiabilidads_idconfiabilidad: 		$scope.postura_existente[0]['confiabilidads_idconfiabilidad'],
						denuncias_iddenuncias: 					$scope.postura_existente[0]['denuncias_iddenuncias'],
						trackings_idtracking: 					$scope.postura_existente[0]['trackings_idtracking'],
						estatusOperaciones_idestatusOperacion: 	$scope.postura_existente[0]['estatusOperaciones_idestatusOperacion'],
						acepta_user_propietario: 				parseInt($scope.postura_existente[0]['acepta_user_propietario']),
						acepta_user_contraparte: 				parseInt($scope.postura_existente[0]['acepta_user_contraparte'])
					};

					$http({ method: 'PUT',
						url: $scope.url_server+'/posturasMatch/{},',
						data:$scope.post_match
					})
					.then(function (data){
						$location.url('/operacion/'+data['data']['data'][0]['idposturasMatch']);
					}
					,function (xhr, ajaxOptions, thrownError){
						console.log("MATCHES_CONT:: Error actualizando postura match: \n Error: "+xhr.status+" "+thrownError);
						console.log(error);
					});
	            }
			}
        }
        ,function (error){
          console.log("MATCHCONT:: Error obteniendo data postura_match_por_posturas");
          console.log(error);
        });
	}

	function cambiar_estatus_postura($idpostura,$idStatus)
	{
		$resp = true;
		// Se actualiza estatus de posturaMatch
		$http({ method: 'PUT',
			url: $scope.url_server+'/posturas/cambiar_estatus_postura/'+$idpostura+'/'+$idStatus})
			.then(function (data){
				console.log("MATCHCONT:: cambiar_estatus_postura "+data['data']['code']+' / '+data['data']['data']);
				console.log(data['data']['message']);
				if( data['data']['data'] == 0)
					$resp = false;
			}
			,function (error){
				console.log("MATCHCONT:: Error actualizando estatus postura");
				console.log(error);
				$resp = false;
			});
			return $resp;
	}

	function cambiar_estatus_operacion($idposturaMatch,$idStatus)
	{
		// Se actualiza estatus de posturaMatch
		$http({ method: 'PUT',
			url: $scope.url_server+'/posturas/cambiar_estatus_operacion/'+$idposturaMatch+'/'+$idStatus})
			.then(function (data){
				console.log("MATCHCONT:: cambiar_estatus_operacion "+data['data']['code']);
				console.log(data['data']['message']);
			}
			,function (error){
				console.log("MATCHCONT:: Error actualizando estatus posturaMacth");
				console.log(error);
			});
	}

	function calcular_tiempo_fecha_neg($fecha)
	{
		$flag_meses 	= false;
		$meses 			= 0;
		$text_neg 		= 'Este usuario no ha concretado una operación';

		if($fecha != null)
		{
			if($scope.validar_navegador() == 2) // Safari
			{
				$text_neg 		= $scope.format_date_db(new Date($fecha.split(' ')[0]),0);
			}
			else
			{
				$fecha_actual 	= new Date();
				$fecha_postura 	= new Date($fecha);

				$diasDif = $fecha_actual.getTime() - $fecha_postura.getTime();
				$dias = Math.round($diasDif/(1000 * 60 * 60 * 24));

				if($dias <= 7)
				{
					// 1. Hace menos de 7 días.
					$text_neg = "Hace "+$dias+" días.";
				}
				else if($dias > 7 && $dias <30) 
				{
					// 2. Hace más de 7 días pero menos de 1 mes
					$text_neg = "Hace "+Math.floor($dias/7)+" semanas.";
				}
				else
				{
					$flag_meses = true;
					$meses = Math.floor($dias/30);
				}


				if($flag_meses)
				{
					if($meses <= 6)
					{
						// 3. Hace más de 4 semanas pero menos que 6 meses.
						$text_neg = "Hace "+$meses+(($meses == 1) ? " mes." : " meses.");
					}
					else
					{
						// 4. Hace más de 6 meses
						$text_neg = $scope.format_date_db($fecha_postura,-1);
					}
				}
			}
		}

		return $text_neg;
	}

	function get_posture_user($id_posture)
	{
		console.log('get_posture_user id = '+$id_posture);
		var posture = {};
		// Se obtiene Postura del id recibido
		$http({ method: 'GET',
				url: $scope.url_server+'/posturas/'+$id_posture })
				.then(function (data){
					posture = data['data']['data'][0];
					
					if($scope.validar_navegador() == 2) // Safari
					{
						posture.fechahasta = $scope.format_date_db(new Date(data['data']['data'][0]['fechahasta'].split(' ')[0]),0);
					}
					else
					{
						posture.fechahasta = $scope.format_date_db(new Date(data['data']['data'][0]['fechahasta']),0);
					}
					
					if((data['data']['data'][0]['user']['operaciones']['primera'] != null)
						&& (data['data']['data'][0]['user']['operaciones']['ultima'] != null))
					{
						posture.text_primera_negociacion 	= calcular_tiempo_fecha_neg(data['data']['data'][0]['user']['operaciones']['primera']['updated_at']);
						posture.text_ultima_negociacion 	= calcular_tiempo_fecha_neg(data['data']['data'][0]['user']['operaciones']['ultima']['updated_at']);
					}
					else
					{
						posture.text_primera_negociacion 	= $scope.text_primera_negociacion;
						posture.text_ultima_negociacion 	= $scope.text_ultima_negociacion;
					}

					posture.amigos_en_comun = get_amigos_en_comun($cookieStore.get('id'),data['data']['data'][0]['user']['id']);
					$scope.posture_user = posture;
				}
				,function (error){
					console.log("get_posture_user:: Error obteniendo data postura: ");
					console.log(error);
				});

		// return posture;
	}

	function get_postura_contraparte($id_posture)
	{
		console.log('get_postura_contraparte id = '+$id_posture);
		var posture = {};
		// Se obtiene Postura del id recibido
		$http({ method: 'GET',
				url: $scope.url_server+'/posturas/'+$id_posture })
				.then(function (data){
					posture = data['data']['data'][0];
					
					if($scope.validar_navegador() == 2) // Safari
					{
						posture.fechahasta = $scope.format_date_db(new Date(data['data']['data'][0]['fechahasta'].split(' ')[0]),0);
					}
					else
					{
						posture.fechahasta = $scope.format_date_db(new Date(data['data']['data'][0]['fechahasta']),0);
					}
					// console.log((data['data']['data'][0]['user']['operaciones']['primera'])
					if((data['data']['data'][0]['user']['operaciones']['primera'] != null)
						&& (data['data']['data'][0]['user']['operaciones']['ultima'] != null))
					{
						console.log('en el if ///////////////')
						posture.text_primera_negociacion 	= calcular_tiempo_fecha_neg(data['data']['data'][0]['user']['operaciones']['primera']['updated_at']);
						posture.text_ultima_negociacion 	= calcular_tiempo_fecha_neg(data['data']['data'][0]['user']['operaciones']['ultima']['updated_at']);
						console.log('success get_postura_contraparte')
						console.log(posture)
					}
					else
					{
						posture.text_primera_negociacion 	= $scope.text_primera_negociacion;
						posture.text_ultima_negociacion 	= $scope.text_ultima_negociacion;
					}

					posture.amigos_en_comun 			= get_amigos_en_comun($cookieStore.get('id'),data['data']['data'][0]['user']['id']);
					$scope.postura_contraparte = posture;
				}
				,function (error){
					console.log("get_postura_contraparte:: Error obteniendo data postura: ");
					console.log(error);
				});

		// return posture;
	}

	$scope.redireccion_amigoscomun = function (idpostura){
		console.log('ya va '+$cookieStore.get('id')+' y idpostura es '+idpostura+' y '+$routeParams.id_postura_contraparte);
		$scope.idsesion = $cookieStore.get('id');
		$scope.idposturaM = idpostura;
		console.log('/amigos/amigoscomun/'+$scope.idsesion+'/contraparte/'+$scope.idposturaM+'/match/'+$routeParams.id_postura_contraparte);
		$location.url('/amigos/amigoscomun/'+$scope.idsesion+'/contraparte/'+$scope.idposturaM+'/match/'+$routeParams.id_postura_contraparte);		
	}

	$scope.carga_datos_usuarios = function (){
		console.log('aqui 1 '+$routeParams.id);
		console.log('aqui 2 '+$routeParams.id_postura);
		console.log('aqui 3 '+$routeParams.id_posturas_match);
		
		//DATOS DE USUARIO COTRAPARTE
		$http({ method: 'GET',url: $scope.url_server+'/posturas/'+$routeParams.id_posturas_match})
		.then(function (data){
			console.log('resultado de data '+data['data']['data']);
			if(data['data']['data']){
				//TRAEMOS EL ID Y EL USERNAME DE LA CONTRAPARTE
				for(var i in data['data']['data']){
					console.log('itera sobre user '+data['data']['data'][i].user);
					$scope.objUser = data['data']['data'][i].user;
					$scope.idcontraparte = data['data']['data'][i].user.id;
					$scope.get_amigos_c($scope.idcontraparte);
				}

			}else{
				console.log("La consulta no trajo registros de posturas");
			}
		}
		,function (error){
					console.log("get_usuarios_match:: Error obteniendo data get_usuarios_match: ");
					console.log(error);
		});

	}


	$scope.post_current_page = 0;
    $scope.post_page_size = 5;
    $scope.post_pages = [];

	$scope.get_amigos_c = function(idcontraparte){
		console.log('idsesion '+$routeParams.id);
		console.log('idcontraparte '+idcontraparte);
		//TRAEMOS LOS AMIGOS EN COMUN DE AMBAS PARTES
		console.log('/amigos/amigos_en_comun_tabla/'+$routeParams.id+'/'+idcontraparte);
		
		$http({ method: 'GET',url: $scope.url_server+'/amigos/amigos_en_comun_tabla/'+$routeParams.id+'/'+idcontraparte})
		.then(function (data){
			if(data['data']['data'].length > 0){
				$scope.amigoscomun = data['data']['data']
				console.log('amigos en comun '+data['data']['data'][0].username);
				$scope.rank = $sce.trustAsHtml(data['data']['data'][0].ranking);
			}else{
				console.log("La consulta no trajo registros de amigos en comun");
			}

		}
		,function (error){
					console.log("get_usuarios_match:: Error obteniendo data amigos_en_comun: ");
					console.log(error);
		});
	}


	//item checkados en la tabla de confiabilidad
	$scope.selected = [];
	$scope.botonHab=0;

	$scope.exist = function(item){
		console.log("aqui exist "+$scope.selected);
		return $scope.selected.indexOf(item) > -1;
	}

	$scope.toggleSelection = function(item){
		console.log("aqui toggle "+item);
		var idx = $scope.selected.indexOf(item);
		console.log("valor de idx afuera "+idx);
		if(idx > -1){
			$scope.selected.splice(idx,1);
			console.log("valor de idx "+idx);
			if(idx === 0){
				$scope.botonHab = 0;
			}
		}else{
			$scope.botonHab = 1;
			$scope.selected.push(item);
			console.log("ahora si "+$scope.selected);
		}
	}


	$scope.checkAll = function(){
		console.log("aqui seleccionar todo "+$scope.selectAll);
		if($scope.selectAll){
			$scope.botonHab = 1;
			angular.forEach($scope.amigoscomun,function(item){
				idx = $scope.selected.indexOf(item);
				if(idx >= 0){
					console.log("seleccionados todos");
					return true;
				}else{
					console.log("seleccionados ninguno");
					$scope.selected.push(item);
				}
			})
		}else{
			$scope.botonHab = 0;
			$scope.selected = [];
		}
	}

	//registro en la tabla de confiabilidad
	$scope.conf_confiabilidad = function(){
		$scope.botonHab = 0;
		console.log("valor item itemmm "+$scope.selected);
		console.log("valor contraparte "+$scope.idcontraparte);
		console.log("long conf "+$scope.selected.length);
		var band=$scope.selected.length;
		band = band;
		$scope.mensajeConf=0;
		var dat=[];
		for(var i in $scope.selected){
			 var data = {
			 		'idsesion' : $routeParams.id,
					'idusersolicitconfiab' : $scope.selected[i].iduser,
					'iduserrecomconfiab' : $scope.idcontraparte,
					'estatus' : 0,
					'comentario' : 'iduser '+$routeParams.id+' solicita confirmacion de confiabilidad'
			};
			console.log('val data '+$scope.selected.length);

			$http({ method: 'POST',url: $scope.url_server+'/confiabilidad',data:data})
			.then(function (data){
				call_modal(dat,data['data']['data'],band);
			}
			,function (xhr, ajaxOptions, thrownError){ 
					console.log("POSTCONT:: Error al enviar confiabilidad: \n Error: "+xhr.status+" "+thrownError);
					$scope.mensajeConf=1;
			});
		}
		
	}

	function call_modal(dat,data,band){
		
		dat.push(data);
		console.log('longitud  es '+dat.length);

		if(dat.length == band){
				$('#modalTempConfiabilidad').modal('show');
			$timeout( function(){
            	$('#modalTempConfiabilidad').modal('hide');
       		}, 1500 );
       		$timeout( function(){
            	$location.url('/postures/new');	
       		}, 2000 );
       			
		}

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



    $scope.init_negotiation_detail = function()
	{
		if(($routeParams.id_postura_match))
		{
			$scope.get_postura_match($routeParams.id_postura_match);
			$scope.current_user_id = $cookieStore.get('id');
			$scope.mensaje_recalificacion = "Luego de cerrar la negociación tu puedes recalificar en los próximos 30 días.";
		}
	}

	$scope.get_postura_match = function($id_postura_match)
	{
		console.log('----------------get_postura_match---------------'+$id_postura_match)
		// Se obtiene Postura del id recibido
		if($id_postura_match != undefined)
		{
			$http({ method: 'GET',
					url: $scope.url_server+'/posturasMatch/'+$id_postura_match })
					.then(function (data){
						$scope.postura_match_det = data['data']['data'][0];

						if(data['data']['data'].length > 0)
						{
							// Se da nuevo formato a fechas del log de la postura match
							if(Object.keys($scope.postura_match_det['log']).length > 0)
							{
								$scope.postura_match_det['log'].forEach(function(log) {
									if($scope.validar_navegador() == 2) // Safari
									{
										log['created_at'] = $scope.format_date_db(new Date(log['created_at'].split(' ')[0]),0);
									}
									else
									{
										log['created_at'] = $scope.format_date_db(new Date(log['created_at']),0);
									}
						        });
							}

							// Valido que las posturas contengan informacion
							if(	(Object.keys($scope.postura_match_det['postura'][0]).length > 0)
								&& (Object.keys($scope.postura_match_det['postura_contraparte'][0]).length > 0))
							{						
								// Se da nuevo formato a fechas del log de cada postura (propietario y contraparte)
								console.log(($scope.postura_match_det['postura'][0]['usuario']['log'].length))
								if(Object.keys($scope.postura_match_det['postura'][0]['usuario']['log']).length > 0)
								{
									$scope.postura_match_det['postura'][0]['usuario']['log'].forEach(function(log) {
										if($scope.validar_navegador() == 2) // Safari
										{
											log['created_at'] = $scope.format_date_db(new Date(log['created_at'].split(' ')[0]),0);
										}
										else
										{
											log['created_at'] = $scope.format_date_db(new Date(log['created_at']),0);
										}

							        });
								}
								if(Object.keys($scope.postura_match_det['postura_contraparte'][0]['usuario']['log']).length > 0)
								{
						        	$scope.postura_match_det['postura_contraparte'][0]['usuario']['log'].forEach(function(log) {
										if($scope.validar_navegador() == 2) // Safari
										{
											log['created_at'] = $scope.format_date_db(new Date(log['created_at'].split(' ')[0]),0);
										}
										else
										{
											log['created_at'] = $scope.format_date_db(new Date(log['created_at']),0);
										}
						        	});
						        }
							}
							else
							{
								posture_match.text_postura 	= "problemas cargando la información, comuniquese con el administrador";
								posture_match.text_postura_contraparte	= "problemas cargando la información, comuniquese con el administrador";
							}
						}
						else
						{
							$location.url('/');
							alert("Dirección solicitada no encontrada.");
						}
					}
					,function (error){
						console.log("get_postura_match:: Error obteniendo data postura match: ");
						console.log(error);
					});

			// return posture;
		}
	}



}]);


