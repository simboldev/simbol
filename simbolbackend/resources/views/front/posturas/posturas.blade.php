@extends('front.template.front')
@section('title','Posturas')
@section('content')


<table class="table table-striped" >
	<tbody>
		<td>
			@include('front.template.partials.menu')
		</td>
		<td>
			<div class="Rectangle-3" style="margin-left: -10px;margin-top: -10px;padding-top: 20px;">
				<!--formulario postura-->
				<div class="Rectangle-Copy" style="margin-left: 20px;display: inline-block;">
					<table class="table table-striped" >
						<thead>
							<td>
								<div class="Quiero" style="margin-left: 90px;padding-top: 38px;">
									Quiero
								</div>
							</td>
							<td>
								<div class="Tengo" style="margin-left:120;padding-top: 38px;">
									Tengo
								</div>
							</td>
							<td>
								<div class="Tasa-de-Cambio" style="margin-left:90px;padding-top: 38px;">
									Tasa de Cambio
								</div>
							</td>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="USDB" style="padding-top: 20px;margin-left:25px;">
										<select id="denomnacion">
											<option>USD</option>
											<option>BsF</option>
										</select>
										<div class="Rectangle-2" style="margin-left: 58px;margin-top: 10px;"></div>
									</div>

								</td>
								<td>
									<div class="Bs-F-1600000" style="padding-top: 20px;margin-left:-60px;">
										BsF <input type="text" name="montoTengo" id="montoTengo" value="1.600.000" style="width: 130px;">
										<div class="Rectangle-2-Copy" style="margin-left: 18px;margin-top: 10px;"></div>
									</div>
								</td>
								<td>
									<div class="Bs-F-21500" style="padding-top: 20px;margin-left:80px;">
										BsF <input type="text" name="tasaCambio" id="tasaCambio" value="21.500" style="width: 90px;">
										<div class="Rectangle-2-Copy-2" style="margin-left: 03px;margin-top: 10px;"></div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<table class="table table-striped" >
						<thead>
							<td >
								<div class="Tiempo-disponible" style="margin-top: 50px;margin-left: 90px;width: 140px;">
									Tiempo Disponible
								</div>
							</td>
							<td colspan="2">
								<div class="Bancos" style="margin-top: 45px;margin-left: 90px;">
									Bancos
								</div>
							</td>
						</thead>
						<tbody>
							<tr>
								<td>
									<table class="table table-striped">
										<tr>
											<td>
												<div class="Hasta-el" style="margin-left:20px;width: 10px;">
													<select id="ragos">
														<option>Hasta el</option>
													</select>
													<div class="Rectangle-2-Copy-4" style="margin-left: -5px;margin-top: 10px;"></div>
												</div>
											</td>
											<td>
												<div class="layerb" style="margin-left: 140px;margin-top: -04px;">
													<input type="date" name="fecha">
												</div>
												<div class="Rectangle-2-Copy-3" style="margin-left: 190px;margin-top: 17px;"></div>
											</td>
										</tr>
									</table>
								</td>
								<td>
										<table class="table table-striped">
											<tr>
												<td>
													<div class="Bolivares" style="margin-left: -0px;">
														Bolivares
													</div>
												</td>
												<td>
													<div class="USDC" style="margin-left: 50px;">
														USD
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div style="margin-left: -10px;">
														<select multiple="true">
															<option>Mercantil</option>
															<option>BNC</option>
															<option>Provincial</option>
														</select>
													</div>
													
												</td>
												<td>
													<div >
														<select multiple="true">
															<option>Bank of America</option>
															<option>Wells Fargo</option>
															<option>Citybank</option>
														</select>
													</div>
												</td>
											</tr>
										</table>		
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="Publicar-Postura" style="margin-top:-800px;margin-left:320px;">
				<input type="button" name="publicarPostura" id="publicarPostura" class="Rectangle-Copy-btt" value="Publicar Postura">
			</div>
	

			
			<div style="margin-top: 20px;">
					<div  class="Mis-Posturas" >Mis Posturas</div>
				<!-- BUSCADOR -->
					<div class="input-group" style="margin-left: 550px;">
						<span class="input-group-addon" id="search" >
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</span>
						<input type="search" name="buscar" id="buscar" placeholder="Buscar Posturas..." aria-describedby="search" class="form-control" style="width: 300px;height: 50px;">
					</div>
				<!-- fin buscador -->
					<hr>
					<table class="Rectangle-Copy-2" style="text-align: center;margin-left: 35px;">
						<thead>
							<td>ID</td>
							<td>Tasa de Cambio</td>
							<td>Quieres</td>
							<td>Tienes</td>
							<td>Usuario</td>
							<td>Disponible</td>
						</thead>
						<tbody>
							<tr>
								<td>7895</td>
								<td>21.500</td>
								<td>$500</td>
								<td>BsF 11.000.000</td>
								<td>DHG56</td>
								<td>29/09/2017</td>
							</tr>
							<tr>
								<td>8953</td>
								<td>21.600</td>
								<td>8.000.000</td>
								<td>$450</td>
								<td>TR74S</td>
								<td>22/09/2017</td>
							</tr>
						</tbody>
					</table>
					<table class="Rectangle-Copy-2" style="text-align: right;width: 150px;margin-left: 700px;margin-top: 20px">
						<thead>
							<th><</th>
							<th>1</th>
							<th>2</th>
							<th>></th>
						</thead>
					</table>
			</div>

			<div style="margin-top: 20px;">
					<div  class="Mis-Posturas" >Mis Negociaciones</div>
				<!-- BUSCADOR -->
					<div class="input-group" style="margin-left: 550px;">
						<span class="input-group-addon" id="search" >
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</span>
						<input type="search" name="buscar" id="buscar" placeholder="Buscar Negociaciones..." aria-describedby="search" class="form-control" style="width: 300px;height: 50px;">
					</div>
				<!-- fin buscador -->
					<hr>
					<table class="Rectangle-Copy-2" style="text-align: center;margin-left: 35px;">
						<thead>
							<td>ID</td>
							<td>Tasa de Cambio</td>
							<td>Quieres</td>
							<td>Tienes</td>
							<td>Usuario</td>
							<td>Disponible</td>
						</thead>
						<tbody>
							<tr>
								<td>7895</td>
								<td>21.500</td>
								<td>$500</td>
								<td>BsF 11.000.000</td>
								<td>DHG56</td>
								<td>54 min</td>
							</tr>
							<tr>
								<td>8953</td>
								<td>21.600</td>
								<td>8.000.000</td>
								<td>$450</td>
								<td>TR74S</td>
								<td>34 min</td>
							</tr>
						</tbody>
					</table>
					<table class="Rectangle-Copy-2" style="text-align: right;width: 150px;margin-left: 700px;margin-top: 20px">
						<thead>
							<th><</th>
							<th>1</th>
							<th>2</th>
							<th>></th>
						</thead>
					</table>
			</div>
		</td>
		<td>
			<div class="Rectangle-3-Copy" style="margin-top: -10px;padding-top: 25px;">
				<!--tasa de cambio -->
				<div class="Rectangle-Copy-5" style="margin-left: 50px;margin-bottom: -30px;position:relative">
						<div class="Tasa-de-Cambio-Copy-2" style="margin-left:35px;">
								Tasa de Cambio
								<p class="-de-Septiembre-201-Copy" style="margin-left: -25px;">
									16 de Septiembre 2017 - 12:30 pm
								</p>
						</div>
				</div>
				<div style="margin-left: 30px;padding-top: 35px;" class="Rectangle-Copy-17">
					
					<div class="Rectangle-Copy-15" style="padding-top: 15px;margin-left: 13px;">
						<div class="USD-Copy">BsF 21.700/USD</div>
					</div>
				</div>

				<!-- apertura de valor de moneda -->
				<div class="Rectangle-Copy-19" style="margin-left: 50px;margin-bottom: -30px;position:relative;margin-top: 20px;">
					<div class="Hoy-abrio-en" style="margin-left: 45px;padding-top: 10px;">
						Hoy abrio en 
					</div>
				</div>
				<div class="Rectangle-Copy-18" style="margin-left: 32px;margin-top: 15px;padding-top: 30px;">
					<div class="Rectangle-Copy-20" style="margin-left: 12px;">
						<div class="USD" style="padding-top: 20px;">
							BsF 22.700/USD
						</div>
					</div>
				</div>
		
				<!--oferta y demanda -->
				<div class="Rectangle-Copy-10" style="margin-left: 55px; margin-top: 20px;position:relative;">
					<div class="Oferta-y-Demanda" style="margin-left: 20px;padding-top: 10px;">
						Oferta y Demanda
					</div>
				</div>
				<div class="Rectangle-Copy-6" style="margin-left: 35px;margin-top: -35px;padding-top:50px;">
					<div class="Ofertas-abiertas" style="margin-left: 20px;">
						Ofertas abiertas
					</div>
					<div class="layer" style="margin-left: 35px;margin-top: -35px;padding-top:35px;">
						1658
					</div>
					<div class="Demandas-abiertas" style="margin-left: 20px;margin-top: -35px;padding-top:90px;">
						Demandas abiertas
					</div>
					<div class="layer" style="margin-left: 35px;margin-top: -35px;padding-top:55px;">
						2652
					</div>
				</div>

				<!-- mejor del día -->
				<div class="Rectangle-Copy-12" style="margin-left: 60px;margin-top: 20px;position:relative;">
					<div class="Mejor-del-Dia" style="margin-left: 40px;padding-top: 10px;">
						Mejor del Dia
					</div>
				</div>
				<div class="Rectangle-Copy-11" style="margin-left: 40px;margin-top: -30px;">
						<div class="Venta" style="padding-top: 40px;margin-left: 20px;">
							Venta
						</div>
						<div class="USDB" style="padding-top:30px;margin-left: 20px;">
							BsF 21.800/USD
						</div>

						<div class="Compra" style="padding-top: 40px;margin-left: 20px;">
							Compra
						</div>
						<div class="USDB" style="padding-top:30px;margin-left: 20px;">
							BsF 21.500/USD
						</div>
				</div>
				<div style="margin-top: 10px;margin-left: 1px;">
					
				</div>
				@include('front.template.partials.chat')
			</div>
		</td>
	</tbody>
</table>

@endsection