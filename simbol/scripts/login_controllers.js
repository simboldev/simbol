mainApp
.controller('LoginCtrl',['$scope','$http','$sce','$window','$cookies',function($scope,$http,$sce,$window,$cookies){
	
	//Método de cargar objetos y valores al cargar la sección
	$scope.cargaPrimaria = function(){
		console.log("se carga valores para el inicio de esta sección");
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
			$http({method:'GET',url: $scope.url_server+'/users/'+$scope.userR})
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

	//Método para validar que el número de carácteres sea 8 para el pass y rePass
	$scope.valNumCarac = function(){

		if($scope.passwordR){
			if($scope.passwordR < 8){
				console.log($scope.passwordR);
				$scope.valnumcarPass="Correcto...";
			}else{
				console.log($scope.passwordR);
				$scope.valnumcarPass="Introduzca 8 ó más carácteres...";
			}
		}
	}

	//Método que compara ambos campos de passwords
	$scope.igualdadPass = function(){
		if(angular.equals($scope.passwordR, $scope.passwordRR)){
			$scope.botonR=2;
		}else{
			$scope.botonR=1;
		}
	}


	//Método que modifica el password para la recuperación de contraseñas
	$scope.modifPass = function(){
		console.log($scope.passwordR+"valor de la contraseña a modificar");
		$scope.id=1;
		$scope.username='amaroc';
		$scope.email='carlosamaro1@gmail.com';
		$scope.estatususuario=1;
		$scope.recomendado_por_user_id=0;
		$scope.tipousuario_idtipousuario=1;
		$scope.verification_token='5we33122';
		$scope.online=0;

		$scope.recPass = {
			id:$scope.id,
			username:$scope.username,
			password:$scope.passwordR,
			remember_token:$scope.passwordR,
			email:$scope.email,
			estatususuario:$scope.estatususuario,
			recomendado_por_user_id:$scope.recomendado_por_user_id,
			tipousuario_idtipousuario:$scope.tipousuario_idtipousuario,
			verification_token:$scope.verification_token,
			online:$scope.online
		};

		
		console.log($scope.recPass.id);
		$http.put($scope.url_server+'/users/'+$scope.id,$scope.recPass)
		.then(function(response){
			
			console.log(response+"se modifico");
			$scope.passwordR="";
			$scope.passwordRR="";
			$scope.valnumcarPass="";
			$scope.valnumcarRePass="";
		},function(response){
				console.log("servicio no existe")
		})
	
	}


	//Método que activa el botón iniciar sesión una vez validado que los campos de textos contengan caracteres
	$scope.activaBoton = function (){
		
			if(angular.isUndefined($scope.user) && angular.isUndefined($scope.password)){

				$scope.boton = 1;

			}else if(!angular.isUndefined($scope.user) && !angular.isUndefined($scope.password)){

				$scope.boton = 2;

			}else if(!angular.isUndefined($scope.user) && angular.isUndefined($scope.password)){

				$scope.boton = 3;

			}else if(angular.isUndefined($scope.user) && !angular.isUndefined($scope.password)){

				$scope.boton = 4;

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


}]);
