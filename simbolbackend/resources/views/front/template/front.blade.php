<!DOCTYPE html ng-app="simbol">
<html>
<head>
	<meta charset="utf-8">
	<title>@yield('title','Default') | Posturas </title>
	<link rel="stylesheet"  href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet"  href="{{ asset('css/simbol.css') }}">

	<script src="<?= asset('app/bower_components/angular/angular.js') ?>"></script>
	<script src="<?= asset('app/bower_components/angular-route/angular-route.js') ?>"></script>
	<script src="<?= asset('app/bower_components/angular-resource/angular-resource.js') ?>"></script>
	<script src="<?= asset('app/script/app.js') ?>"></script>

	

</head>
<body class="Symbol---P1">
	@include('front.template.partials.head')
	
	<section class="section-body">
		<div class="panel panel-default" >
			<div class="panel -body">
				@yield('content')
			</div>
		</div>
	</section>

	<!--<script type="text/javascript" src="{{ asset('app/bower_components/angular/angular.js') }}"></script>
	<script type="text/javascript" src="{{ asset('app/bower_components/angular-route/angular-route.js') }}"></script>
	<script type="text/javascript" src="{{ asset('app/bower_components/angular-resource/angular-resource.js') }}"></script>
	<script type="text/javascript" src="{{ asset('app/script/app.js') }}"></script>-->
	<!--<script src="<?= asset('app/lib/angular/angular.min.js') ?>"></script>-->
    <!--<script src="<?= asset('plugins/bootstrap/css/bootstrap.min.js') ?>"></script>--> 
        <!-- AngularJS Application Scripts -->
    <!--<script src="<?= asset('app/app.js') ?>"></script>-->
    <!--<script src="<?= asset('app/controllers/chat.js') ?>"></script>-->
    
</body>
</html>