mainApp
.controller('PostureCtrl',['$scope','posture','$http','$cookieStore','$location','$routeParams','$interval',function($scope,posture,$http,$cookieStore,$location,$routeParams,$interval){

	$scope.tittle_page += "| Nueva Postura";

	$scope.monedas = [];
	$scope.iwant_opt = "";
	$scope.ihave_opt = "";
	$scope.pattern_i_have = /^[0-9]+([,][0-9]+)+(?:\.\d{1,2})?$/;// || /^\d+(?:\.\d{1,2})?$/;
	$scope.d = new Date();
  $scope.fecha_desde = new Date();
  $scope.fecha_desde.setHours(0);
  $scope.fecha_desde.setMinutes(0);
  $scope.fecha_desde.setSeconds(0);
  $scope.monedaBs = [1];
  $scope.monedaInternacional = [2];
	$scope.datestring = $scope.d.getDate()  
	+ "-" + ($scope.d.getMonth()+1) 
	+ "-" + $scope.d.getFullYear()
	+ " " +$scope.d.getHours() 
	+ ":" + $scope.d.getMinutes();

	$scope.timestring = new Date($scope.d.getFullYear(), $scope.d.getMonth(), $scope.d.getDate(), $scope.d.getHours(), $scope.d.getMinutes()+60, 0);

	// Monedas registradas para el intercambio en el sistemaheaders_json
	$http({ method: 'GET',
          url: $scope.url_server+'/monedas' 
      })
			.then(function (data){
				$scope.monedas = data['data']['data'];
				$scope.iwant_opt = $scope.monedas[0]['admin_simbolo'];
				$scope.ihave_opt = $scope.monedas[1]['admin_simbolo'];
        $scope.iwant_opt_s = $scope.monedas[0]['admin_simbolo'];
        $scope.ihave_opt_s = $scope.monedas[1]['admin_simbolo'];
        console.log("========================= "+$scope.ihave_opt_s);
			}
			,function (error){ 
				console.log("POSTCONT:: Error obteniendo data monedas: "+error)
			});

  

	$scope.posture_initial = {
		iwant_id: 1,
		ihave_id: 2,
		ihave: 0.01,
		rate: 0.01,
		availableTime_date: $scope.fecha_desde,//$scope.datestring.split(' ')[0],
		availableTime_time: $scope.timestring, // (formatted: 7:29 PM)"",
		availableTime_condition: 1,
		banks_bs_selected : "",
  	banks_usd_selected : "",
  	fraccionar: {title:"Permitir fraccionar la postura", value: 1},
		banks_bs: [],
  	banks_bs_list: [],
  	banks_usd: [],
		banks_usd_list: []
	}

  // Actualizo la hora
  // $interval(function()
  // {
  //   $scope.current_date = new Date();
  //   $scope.posture_initial.availableTime_time = new Date($scope.current_date.getFullYear(), $scope.current_date.getMonth(), $scope.current_date.getDate(), $scope.current_date.getHours(), $scope.current_date.getMinutes()+60, $scope.current_date.getMilliseconds());
  // },60000);

	// Bancos en Bolivares del usuario registrados para el intercambio en el sistema
	$http({ method: 'GET',
			url: $scope.url_server+'/users/1/banks/1'
      })
			.then(function (data){
				$scope.posture.banks_bs_list = data['data']['data'];
			}
			,function (error){ 
				console.log("POSTCONT:: Error obteniendo data banks 1: "+error)
			});

	// Bancos en USD del usuario registrados para el intercambio en el sistema
	$http({ method: 'GET',
			url: $scope.url_server+'/users/1/banks/2' })
			.then(function (data){
				$scope.posture.banks_usd_list = data['data']['data'];
			}
			,function (error){ 
				console.log("POSTCONT:: Error obteniendo data banks 2: "+error)
			});

	$scope.posture = $scope.posture_initial;

    $scope.availableTime_condition = [
        {'id': 1, 'condition': 'Hoy'}
        // {'id': 2, 'condition': 'Una semana'},
        // {'id': 3, 'condition': 'Un mes'},
        // {'id': 4, 'condition': 'Hasta el'},
        // {'id': 5, 'condition': 'Sin límite'}
    ];

	/* Validacion al escribir algo en el 
		autocomplete Banco BsF y permite agregar
		los tags de los bancos
	*/
	$scope.$watch('posture.banks_bs_selected', function () {

	    var objectConstructor = {}.constructor;
	    // Valido si el objeto es json
	    if ($scope.posture.banks_bs_selected.constructor === objectConstructor) {
	        /* Obteniendo index del objeto a eliminar */
	        var data = $scope.posture.banks_bs_list;
	        var index_banks_bs_selected = data.map(function(d) { return d['idusers_bancos_pais_monedas']; }).indexOf($scope.posture.banks_bs_selected['idusers_bancos_pais_monedas']);

	        $scope.posture.banks_bs.push($scope.posture.banks_bs_selected);
	        $scope.posture.banks_bs_selected = "";

	        /* Elimino objeto json del arreglo por su index */
	        $scope.posture.banks_bs_list.splice(index_banks_bs_selected, 1);
	    }
	    $scope.validate_inputs_banks(0);
	}, true);

	/* Validacion al escribir algo en el 
		autocomplete Banco USD y permite agregar
		los tags de los bancos
	*/
	$scope.$watch('posture.banks_usd_selected', function () {

	    var objectConstructor = {}.constructor;
	    // Valido si el objeto es json
	    if ($scope.posture.banks_usd_selected.constructor === objectConstructor) {
	        /* Obteniendo index del objeto a eliminar */
	        var data = $scope.posture.banks_usd_list;
	        var index_banks_usd_selected = data.map(function(d) { return d['idusers_bancos_pais_monedas']; }).indexOf($scope.posture.banks_usd_selected['idusers_bancos_pais_monedas']);

	        $scope.posture.banks_usd.push($scope.posture.banks_usd_selected);
	        $scope.posture.banks_usd_selected = "";

	        /* Elimino objeto json del arreglo por su index */
	        $scope.posture.banks_usd_list.splice(index_banks_usd_selected, 1);
	    }
	    $scope.validate_inputs_banks(1);
	}, true);

	/* Funcion que se activa al hacer clic sobre
		boton rojo con la "x" para eliminar un tag banco
	*/
    $scope.deleteBank = function (index,type_bank) {
        var val_deleted = {};
        switch(type_bank) {
    	    case 0: // Bank Bsf
    	    	// if($scope.posture.banks_bs.length > 1)
    	    		/* Agrego el objeto json que estoy 
    	    			eliminando de la lista de tags
    	    			bancos a la lista del autocomplete
    	    			banco_bs 
        			*/
        			val_deleted = $scope.posture.banks_bs.splice(index, 1);
                    $scope.posture.banks_bs_list.push(val_deleted[0]);
    	    		$scope.validate_inputs_banks(0);
    	        break;
    	    case 1: // Bank USD
    	    	// if($scope.posture.banks_usd.length > 1)
    	    		/* Agrego el objeto json que estoy 
    	    			eliminando de la lista de tags
    	    			bancos a la lista del autocomplete
    	    			banco_usd 
        			*/
        			val_deleted = $scope.posture.banks_usd.splice(index, 1);
    	    		$scope.posture.banks_usd_list.push(val_deleted[0]);
    	    		$scope.validate_inputs_banks(1);
    	        break;
    		}
    }

    $scope.change_coin_have_rate = function()
    {
    	//  Cambio labels de BsF a USD y viceversa
	    for (var i = 0; i < $scope.monedas.length; i++) {
	        if ($scope.monedas[i]['idmonedas'] != $scope.posture.iwant_id)
	        {
	            $scope.posture.ihave_id = $scope.monedas[i]['idmonedas'];
	            $scope.ihave_opt = $scope.monedas[i]['admin_simbolo'];
	        }
	        else
	        {
	        	$scope.iwant_opt = $scope.monedas[i]['admin_simbolo'];
	        }
	    }
	    return null;
    }

	$scope.get_array_banks = function(type_bank)
	{
		var banks_list = [];
		var array_banks = [];

		switch(type_bank)
		{
			case 0: // BsF
				banks_list = $scope.posture.banks_bs;
				break;
			case 1: // USD
				banks_list = $scope.posture.banks_usd;
				break;
		}

		    for (var i = 0; i < banks_list.length; i++) {
		    	array_banks.push(banks_list[i]['idusers_bancos_pais_monedas'])
		    }

		    return array_banks;
	}

	/*
		Se muestra u oculta Validacion de los inputs 
		autocomplete de bancos.
	*/
	$scope.validate_inputs_banks = function($type_bank)
	{
		var resp = true;
		var obj_bank = "";
		var id_bank = "";
		switch($type_bank)
		{
			case 0: // BsF
				id_bank = "#posture_banks_bs";
				obj_bank = $scope.posture.banks_bs;
				break;
			case 1: // USD
				id_bank = "#posture_banks_usd";
				obj_bank = $scope.posture.banks_usd;
				break;
		}

		if( obj_bank.length == 0)
		{
			$(id_bank).removeClass('hidden');
			resp = false;
		}
		else
		{
			$(id_bank).addClass('hidden');
		}
		return resp;
	};

    /* Transforma la informacion ingresada por el usuario 
      en formato ##,###,###.##
    */
    $scope.input_format_calculator = function($event,$input_id) {
        $input = $("#"+$input_id);
        // skip for arrow keys
        if($event.which >= 37 && $event.which <= 40){
            $event.preventDefault();
        }

        $input.val(function(index, value) {
        return value
          .replace(/\D/g, "")
          .replace(/([0-9])([0-9]{2})$/, '$1.$2')  
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
        ;
        });
    };

    $scope.validate_number = function($opt,$input_id)
    {
        $input = $("#"+$input_id);
        switch($opt)
        {
            case 0: // number positive
                validate_positive_number($input_id);
                validate_input_blank($input_id);
                // validar_cero_a_la_izquierda($input_id);
                break;
        }
    };

    function validate_positive_number($input_id)
    {
        $resp = true;
        // Paso a decimal el numero y valido si es positivo
        $input_val = parseFloat($("#"+$input_id).val().replace(/,/g,'')).toFixed(2);

        if( $input_val <= 0 || $input_val <= 0.00 )
        {
            $('#'+$input_id+'_ng_error_minval').removeClass('ng-hide');
            $resp = false;
        }
        else
            $('#'+$input_id+'_ng_error_minval').addClass('ng-hide');
        
        return $resp;
    };

    function validate_input_blank($input_id)
    {
        $resp = true;
        // Paso a decimal el numero y valido si es positivo
        $input_val = $("#"+$input_id).val();
        if( $input_val == '' )
        {
          $('#'+$input_id+'_ng_error_required').removeClass('ng-hide');
          $resp = true;
        }
        else
          $('#'+$input_id+'_ng_error_required').addClass('ng-hide');
        
        return $resp;
    };

    function validar_cero_a_la_izquierda($input_id)
    {   
        var a = ($("#"+$input_id).val()).toString(10).split("").map(Number);
        if (a[0] == 0)
        {
            var nuevo_valor = parseFloat($("#"+$input_id).val());
            $("#"+$input_id).val(nuevo_valor);
        };
    };

    function calculate_want_have_amount(iwant_id,my_amount,rate) {
      var amount = 0;
      
      switch(iwant_id)
      {
        case 1: // Bs
          amount = my_amount/rate;
          break;

        case 2: // USD
          amount = my_amount*rate;
          break;

      }
      
      return parseFloat(amount).toFixed(2);
    }

    $scope.set_input = function($input_id)
    {
        $("#"+$input_id).val('');
        if ( validate_positive_number($input_id))
          validate_input_blank($input_id);

    }

  function submit_button_disabled_act($option)
  {
    switch($option)
    {
      case 0: // Bloquear
        $("input[type=submit]").attr("disabled",true);
        $("input[type=submit]").val("Guardando información...");
        break;
      case 1: // Activar
        $("input[type=submit]").attr("disabled",false);
        $("input[type=submit]").val("Publicar postura");
        break;
    }
  }

	$scope.submit = function()
	{
    console.log($cookieStore.get('username'))

		if( 	$scope.form_new_posture.$valid
			&&	( !!$scope.form_new_posture.$error.required == false)
			&&	$scope.validate_inputs_banks(0) 
			&& 	$scope.validate_inputs_banks(1)
		)
		{
      $confirm = confirm("¿Confirmas que deseas crear  la nueva postura?");
      if($confirm == true)
      {
        submit_button_disabled_act(0);
        var ihave_amount = calculate_want_have_amount($scope.posture.iwant_id,
          parseFloat($scope.posture.iwant.replace(/,/g,'')).toFixed(2),
          parseFloat($scope.posture.rate.replace(/,/g,'')).toFixed(2)
          );
        var fechadesde = "";
        var fechahasta = "";

        console.log('===================$scope.posture_save=1============')
        console.log($scope.posture_save)
	        $scope.posture_save = {
	            'quiero_moneda_id'  : $scope.posture.iwant_id,
	            'tengo_moneda_id'   : $scope.posture.ihave_id,
	            'tengo'             : ihave_amount,
              'quiero'            : parseFloat($scope.posture.iwant.replace(/,/g,'')).toFixed(2),
	            'tasacambio'        : parseFloat($scope.posture.rate.replace(/,/g,'')).toFixed(2),
	            'fechadesde'        : $scope.format_date_db($scope.posture.availableTime_date,2),
	            'fechahasta'        : $scope.format_date_db($scope.posture.availableTime_time,2),
	            'comentarios'       : '',
	            'iduser'            : $cookieStore.get('id'),
	            'estatusPosturas_idestatusPosturas'	: 1,
	            'fraccionar'		: $scope.posture.fraccionar.value,
	            'bancos_bs'			: $scope.get_array_banks(0),
	            'bancos_usd'		: $scope.get_array_banks(1)

	        }
          console.log('===================$scope.posture_save=1============')
          console.log($scope.posture_save)

        // Creo la postura
  			$http({ method: 'POST',
  					url: $scope.url_server+'/posturas',
  					data: $scope.posture_save})
  					.then(function successCallback(data){
              alert( "Tu postura ha sido registrada." );
  						console.log(data['data']['data']);
              submit_button_disabled_act(1);

              $location.url('postures/'+data['data']['data']+'/matches');
  					}
  					,function errorCallback(xhr, ajaxOptions, thrownError){ 
  						console.log("POSTCONT:: Error al crear postura: \n Error: "+xhr.status+" "+ xhr.responseText+"  / "+thrownError);
              submit_button_disabled_act(1);
  					});
      }
      else
      {
        mensaje = "Has clickado Cancelar";
      }
		}
	}

  $scope.navbar_height_cal = function()
  {
    var height_window = $(window).height();
    var top_logo_simbol = height_window/3;
    $scope.safari_web = ($scope.validar_navegador() == 2) ? true : false;
    if($scope.safari_web == true)
    {
      $('#logo_sombol_init').hide();
      $('#validation_safari').show();
    }
    else
    {
      $('#validation_safari').hide();
      $('#logo_sombol_init').show();
    }

    $(".logo_simbol_body").css("margin-top",top_logo_simbol+'px');
  }
  
  $scope.ver_mi_postura = function($id_posture)
  {
    
  }
  $scope.ver_negociacion = function($posturas_match_id,$postura_contraparte_id)
  {
    $location.url("postures/"+$posturas_match_id+"/match/"+$postura_contraparte_id);
  }
	// MIS POSTURAS
      $scope.post_current_page = 0;
      $scope.post_page_size = 5;
      $scope.post_pages = [];
      $scope.mis_posturas = []; //[{}]
      $scope.listNeg = [];
      $scope.rip = '';

    $scope.irNegociacion = function($idposturamatch){
        $location.url("/operacion/operacion/"+$idposturamatch);
    }      


    /*METODO PARA TRAER LAS NEGOCIACIONES EN PROCESO JUNTO CON LAS POSTURAS QUE HICIERON MATCH*/
    $scope.negList = function(){
      $http({method: 'GET',url: $scope.url_server+'/negociacion'})
      .then(function (data){
          console.log('buenooo neg '+data['data']['data'][0]['divisa1']);
          $scope.listNeg = data['data']['data'];
      },
      function(error){
          console.log("POSTCONT:: Error en la consulta de estatus de  negociacion: "+error)
      });
    }

    //Método para traer las posturas en interfaz de inicio
    $scope.misPosturasList = function(){
      console.log("se inicia la carga de las posturas activas de "+$cookieStore.get('username'));
      $http({method:'GET',
        url: $scope.url_server+'/posturas/lista_posturas/'+$cookieStore.get('id')+'/1/1/0/0'})
      .then(function(data){
        $scope.mis_posturas = data['data']['data'];
        // Se da nuevo formato a la fecha recibida
        data['data']['data'].forEach(function(post) {
          if($scope.validar_navegador() == 2) // Safari
          {
            post.fechahasta = $scope.format_date_db(new Date(post.fechahasta.split(' ')[0]),0);
          }
          else
          {
            post.fechahasta = $scope.format_date_db(new Date(post.fechahasta),0);
          }
        });
      },function (xhr, ajaxOptions, thrownError){ 
            console.log("POSTCONT:: misPosturasList Error al crear postura: \n Error: "+xhr.status+" "+thrownError);
      });
    }

    $scope.configPagesTableNeg = function() {
        $scope.post_pages.length = 0;
        var ini = $scope.post_current_page - 4;
        var fin = $scope.post_current_page + 5;
        if (ini < 1) {
          ini = 1;
          if (Math.ceil($scope.listNeg.length / $scope.post_page_size) > 10)
            fin = 10;
          else
            fin = Math.ceil($scope.listNeg.length / $scope.post_page_size);
        } else {
          if (ini >= Math.ceil($scope.listNeg.length / $scope.post_page_size) - 10) {
            ini = Math.ceil($scope.listNeg.length / $scope.post_page_size) - 10;
            fin = Math.ceil($scope.listNeg.length / $scope.post_page_size);
          }
        }
        if (ini < 1) ini = 1;
        for (var i = ini; i <= fin; i++) {
          $scope.post_pages.push({
            no: i
          });
        }

        if ($scope.post_current_page >= $scope.post_pages.length)
          $scope.post_current_page = 0;//$scope.post_pages.length - 1;
    };

    $scope.configPagesTablePosturas = function() {
        $scope.post_pages.length = 0;
        var ini = $scope.post_current_page - 4;
        var fin = $scope.post_current_page + 5;
        if (ini < 1) {
          ini = 1;
          if (Math.ceil($scope.mis_posturas.length / $scope.post_page_size) > 10)
            fin = 10;
          else
            fin = Math.ceil($scope.mis_posturas.length / $scope.post_page_size);
        } else {
          if (ini >= Math.ceil($scope.mis_posturas.length / $scope.post_page_size) - 10) {
            ini = Math.ceil($scope.mis_posturas.length / $scope.post_page_size) - 10;
            fin = Math.ceil($scope.mis_posturas.length / $scope.post_page_size);
          }
        }
        if (ini < 1) ini = 1;
        for (var i = ini; i <= fin; i++) {
          $scope.post_pages.push({
            no: i
          });
        }

        if ($scope.post_current_page >= $scope.post_pages.length)
          $scope.post_current_page = 0;//$scope.post_pages.length - 1;
    };
    // MIS NEGOCIACIONES
      $scope.talks_current_page = 0;
      $scope.talks_page_size = 5;
      $scope.talks_pages = [];
      $scope.mis_negociaciones = [];

    //Método para traer las posturas en interfaz de inicio
    $scope.misNegociacionesList = function(){
      $http({method:'GET',
        url: $scope.url_server+'/users/'+$cookieStore.get('id')+'/negociaciones'})
      .then(function(data){
        $scope.mis_negociaciones = data['data']['data'];
        // Se da nuevo formato a la fecha recibida
        data['data']['data'].forEach(function(post) {
            if($scope.validar_navegador() == 2) // Safari
            {
              post['postura'][0].fechahasta = $scope.format_date_db(new Date(post['postura'][0].fechahasta.split(' ')[0]),0);
              post['postura_contraparte'][0].fechahasta = $scope.format_date_db(new Date(post['postura_contraparte'][0].fechahasta.split(' ')[0]),0);
            }
            else
            {
              post['postura'][0].fechahasta = $scope.format_date_db(new Date(post['postura'][0].fechahasta),0);
              post['postura_contraparte'][0].fechahasta = $scope.format_date_db(new Date(post['postura_contraparte'][0].fechahasta),0);
            }
        });
      },function (xhr, ajaxOptions, thrownError){ 
            console.log("POSTCONT:: Error al consultar misNegociacionesList : \n Error: "+xhr.status+" "+thrownError);
      });
    }

    $scope.configPagesTableNegociaciones = function() {
        $scope.talks_pages.length = 0;
        var ini = $scope.talks_current_page - 4;
        var fin = $scope.talks_current_page + 5;
        if (ini < 1) {
          ini = 1;
          if (Math.ceil($scope.mis_negociaciones.length / $scope.talks_page_size) > 10)
            fin = 10;
          else
            fin = Math.ceil($scope.mis_negociaciones.length / $scope.talks_page_size);
        } else {
          if (ini >= Math.ceil($scope.mis_negociaciones.length / $scope.talks_page_size) - 10) {
            ini = Math.ceil($scope.mis_negociaciones.length / $scope.talks_page_size) - 10;
            fin = Math.ceil($scope.mis_negociaciones.length / $scope.talks_page_size);
          }
        }
        if (ini < 1) ini = 1;
        for (var i = ini; i <= fin; i++) {
          $scope.talks_pages.push({
            no: i
          });
        }

        if ($scope.talks_current_page >= $scope.talks_pages.length)
        {
          $scope.talks_current_page = 0;//$scope.talks_pages.length - 1;
        }
    };
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

    //Método para traer las posturas en interfaz de inicio
    $scope.conPosturasInit = function(){
      console.log("se inicia la carga de las posturas para la interfaz fuera de sesión");
      $http({method:'GET',
        url: $scope.url_server+'/posturas/lista_posturas/0/0/1/10/1'})
      .then(function(data){
          console.log("data de posturas");
          console.log(data['data']['data']);
          if(data['data']['data'] == ""){
            console.log("objeto vacio");
          }else{
            console.log("objeto lleno");
            $scope.postu = data['data']['data'];
            // Se da nuevo formato a la fecha recibida
            $scope.postu.forEach(function(post) {
              if($scope.validar_navegador() == 2) // Safari
              {
                post.fechahasta = $scope.format_date_db(new Date(post.fechahasta.split(' ')[0]),0);
              }
              else
              {
                post.fechahasta = $scope.format_date_db(new Date(post.fechahasta),0);
              }
            });
          }
      },function(error) {
          console.log("no hay data");
      });
    }

    //Metodo para traer datos de la tabla posturasMatch
    $scope.consPosturasMatch = function(){
      console.log("----------------------"+$routeParams.id_posturas_match);
      $http({method: 'GET',url: $scope.url_server+'/posturasMatch/'+$routeParams.id_posturas_match})
      .then(function (data){
        console.log(data['data']['data'][0].iduser2+"datos posturasMatch");

        if(data['data']['data'] == ""){
          console.log("POSTCONT:: la consulta no trajo datos posturasMatch: ");
        }else{
          
            if($scope.id!=data['data']['data'][0].iduser2){
                $scope.idContraparte = data['data']['data'][0].iduser2;
            }else{
                $scope.idContraparte = data['data']['data'][0].users_idusers;
            }
          
            /*$http({method: 'GET',
              url: $scope.url_server+'/users/'+$scope.idContraparte+'/'+$scope.idContraparte})*/
            $http({method: 'GET',url: $scope.url_server+'/users/'+$scope.idContraparte})
            .then(function (data){
              console.log(data['data']['data'].username+"data usuario");
              if(data['data']['data'] == ""){
                console.log("POSTCONT:: la consulta no trajo datos de users: ");
              }else{
                $scope.usernameContraP = data['data']['data'].username;
                $scope.userIdContraP = data['data']['data'].id;
              }
            });
        }
      }
      ,function(error){
        console.log("POSTCONT:: Error obteniendo data users: "+error)
      }
      );
    }

    //Método para cargar los valores de las estrellas
    $scope.regStar = function(star){
      if(star == 1){
          $scope.star = 5;
          console.log("valor de estrella "+$scope.star);
      }else if(star == 2){
          $scope.star = 4;
          console.log("valor de estrella "+$scope.star);
      }else if(star == 3){
          $scope.star = 3;
          console.log("valor de estrella "+$scope.star);
      }else if(star == 4){
          $scope.star = 2;
          console.log("valor de estrella "+$scope.star);
      }else if(star == 5){
          $scope.star = 1;
          console.log("valor de estrella "+$scope.star);
      }

      $scope.activarBut();
        
    }

    //método para activar o desactivar el boton de calificar
    $scope.activarBut=function(){

      $scope.act=1;
      if(angular.isUndefined($scope.star)){
          $scope.act=0;
      }
      if(angular.isUndefined($scope.comment)){
        $scope.act=0;
      }
      if($scope.comment==""){
         $scope.act=0;
      }
      console.log("act "+$scope.act);
    }

    //Método para registrar la calificación
    $scope.regCalificacion = function(){

        console.log("id user "+$scope.id);
        console.log("id contraparte "+$scope.userIdContraP);
        console.log("estrella registrada "+$scope.star );
        console.log("comment "+$scope.comment);
        var arrDat = {
          'puntos':$scope.star,
          'comentario':$scope.comment,
          'iduser':$scope.id,
          'idusuariocalificado':$scope.userIdContraP,
          'idPosturasMatch': $routeParams.id_posturas_match
        };
        console.log("arreglo :"+arrDat.puntos);

        //SE VERIFICA QUE YA EL ESTATUS DE LA OPERACION VENGA CANCELADA
        var estatusOperaciones_idestatusOperacion = 0;
        $http({method: 'GET',url: $scope.url_server+'/posturasMatch/'+$routeParams.id_posturas_match})  
        .then(function(data){
          console.log("resultado de posturaMatch: "+data['data']['data'][0].estatusOperaciones_idestatusOperacion);
          estatusOperaciones_idestatusOperacion = data['data']['data'][0].estatusOperaciones_idestatusOperacion;
        }
        ,function (xhr, ajaxOptions, thrownError){ 
                    console.log("POSTCONT:: Error al traer los datos de la posturaMatch: \n Error: "+xhr.status+" "+thrownError);
        });


        //inserción en la tabla posturasmatch_has_calificaciones
        $http({method: 'POST',url: $scope.url_server+'/calificaciones',data:arrDat})
        .then(function(data){
            //modificar el estatus de la posturaMatch a concretada
            if(estatusOperaciones_idestatusOperacion != 4){
              estatusOperaciones_idestatusOperacion = 3;
            }
            $http({method: 'PUT',url: $scope.url_server+'/posturas/cambiar_estatus_operacion/'+$routeParams.id_posturas_match+'/'+estatusOperaciones_idestatusOperacion})
            .then(function(data){
              console.log(data['data']['data'][0]);
            }
            ,function (xhr, ajaxOptions, thrownError){ 
                    console.log("POSTCONT:: Error modificar el estatus de operación de la postura: \n Error: "+xhr.status+" "+thrownError);
            });
            
            //consulta si ambos usuarios son amigos
            $http({method: 'GET',url: $scope.url_server+'/amigos/consAmistad/'+$scope.id+'/'+$scope.userIdContraP})
            .then(function(data){

                //console.log(data['data']['data']);
                if(data['data']['data']==""){
                    console.log("no son amigos");
                    $('#modalCalif').modal('show');
                }else{
                    console.log(data['data']['data']+" ya son amigos");
                    $('#myModaInfCalif').modal('show');
                }
            }
            ,function (error){
                console.log("POSTCONT:: Error obteniendo data amigos: "+error);
                $scope.msg1="¡¡Estimado cliente en estos momentos no podemos procesar su solicitud, intente más tarde!!";
            });

        }
        ,function (xhr, ajaxOptions, thrownError){
              console.log("POSTCONT:: Error al crear calificacion: \n Error: "+xhr.status+" "+thrownError);
              $scope.msg2="¡¡Estimado cliente en estos momentos tenemos problemas de comunicaciones, por favor intente más tarde!!";
        });

    }

    //Método para registrar circulo de confianza
    $scope.regCircConf = function(){
      console.log("id usuario sesión "+$scope.id);
      console.log("id usuario contraparte "+$scope.userIdContraP);
      
      var arrDat = {
        'user1':$scope.id, 
        'user2':$scope.userIdContraP
      };
      $http({method: 'POST',url: $scope.url_server+'/amigos',data:arrDat})
      .then(function(data){
          if(data['data']['data']!=""){
              $scope.infoAmigo="¡¡Nuevo usuario agregado a tu circulo de confianza!!";
          }      
      }
      ,function (xhr, ajaxOptions, thrownError){
          console.log("POSTCONT:: Error al agregar nuevo amigo a circulo de confianza:\n Error: "+xhr.status+" "+thrownError);
      });
    }

    //Método para consultar montos X Posturas
    $scope.montosXposturas = function(){
      console.log("entrando en método "+$routeParams.id_posturas_match);
      console.log("el usuario iduser es: "+$scope.id);
      $http({method: 'GET',url: $scope.url_server+'/posturasMatch/montosXposturas/'+$routeParams.id_posturas_match+'/'+$scope.id})
      .then(function(data){
          if(data['data']['data']){
              console.log("montosXposturas - si hay data "+data['data']['data'][0]['tasacambio']);
              console.log("montosXposturas - moneda que quiero  "+data['data']['data'][0]['quiero_moneda_id']);
              console.log("montosXposturas - el id user traido es "+data['data']['data'][0]['iduser']);

              $scope.tasacambio = data['data']['data'][0]['tasacambio'];
              $scope.transferir = data['data']['data'][0]['tengo'];
              $scope.iduser = data['data']['data'][0]['iduser'];
              $scope.idMonedaQuiero = data['data']['data'][0]['quiero_moneda_id'];
              $scope.simMonedaQuiero = data['data']['data'][0]['quiero_moneda_simbolo'];
              $scope.idMonedaTengo = data['data']['data'][0]['tengo_moneda_id'];
              $scope.simMonedaTengo = data['data']['data'][0]['tengo_moneda_simbolo'];
              $scope.idposturasMatchContraparte = data['data']['data'][0]['idposturasMatchContraparte'];
              $scope.idUserContraparte = data['data']['data'][0]['idUserContraparte'];

              if($scope.id == $scope.iduser){
                if(data['data']['data'][0]['quiero_moneda_id']==1)
                  $scope.recibire = $scope.transferir * $scope.tasacambio;
                else if (data['data']['data'][0]['quiero_moneda_id']==2)
                  $scope.recibire = $scope.transferir / $scope.tasacambio;
              }
          }
      }
      ,function (xhr, ajaxOptions, thrownError){
          console.log("POSTCONT:: Error al agregar nuevo amigo a circulo de confianza:\n Error: "+xhr.status+" "+thrownError);
      });
    }
}])
.filter('startFromGrid', function() {
  return function(input, start) {
    if (!input || !input.length) { return; }
    start = +start;
    return input.slice(start);
  }
})
.factory('posture',function($resource){
	return $resource("http://simbolbackend.dev/new_posture/",{
	})
});

// https://bootsnipp.com/snippets/featured/js-table-filter-simple-insensitive
// https://stackoverflow.com/questions/21891229/search-box-in-angular-js
// https://es.stackoverflow.com/questions/14438/buscador-con-ng-repeat-en-angularjs
// https://www.uno-de-piera.com/filtrar-datos-con-angularjs/

// validate route angularjs
