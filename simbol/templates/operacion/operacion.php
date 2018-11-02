<div ng-if="username != undefined ">
	<div >
		<h2>Gesti&oacute;n de la Operaci&oacute;n</h2>
	</div>
	<div  class="row  index_posture_new_posture" class="container"  ng-controller="ChatCtrl" ng-init="refTrack();estatusOperacion();consEstatusNeg();">
		
		<div class="row nomargin nopadding index_posture_new_posture_tittle" class="col-md-12 col-sm-12 col-xs-12">
			<!--style="margin-top: 60px;margin-left: 130px;"-->
		<!--SI EL ROL ES ADMINISTRADOR-->
		<div ng-if="tipousuario_idtipousuario == 5" ng-init="verNegociacion()" >
			<div class="row nomargin nopadding" >
				<div class="Cell block-center" >
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="border-style:solid;height: 350px;">
							<label for="databs">
								Datos Transferencia en Bolivares 
							</label>

							<p style="font-size: 14px;">
								Banco:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{nombrebancoBS}}</b>
								
							</p>
							<p style="font-size: 14px;">
								Num Cuenta:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{nrocuetanegBS}}</b>
							</p>
							<p style="font-size: 14px;">
								Email:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{emailnegBS}}</b>
							</p>
							<p style="font-size: 14px;">
								Num Identificación:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{nroidentificacionnegBS}}</b>
							</p>
							<br>

							
							<div ng-if="estatusnegDL == 3 && estatusnegBS == 2">
								<p>
									<a href="{{ linkDL }}" target="_blank">
										<img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
									</a>
								</p>

								<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="autorizaTransf();">
									Confirmar Transferencia
								</button>
							</div>

							<div ng-if="estatusnegDL == 3 && estatusnegBS == 5">
								<p>
									<a href="{{ linkDL }}" target="_blank">
										<img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
									</a>
								</p>

							</div>

							<div ng-if="estatusnegDL == 5 && estatusnegBS == 5">
								<p>
									<a href="{{ linkDL }}" target="_blank">
										<img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
									</a>
								</p>
							</div>

					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="border-style:solid;height: 350px;">
							<label for="databs">
								Datos Transferencia en Dollares
							</label>

							<p style="font-size: 14px;">
								Banco:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{nombrebancoDL}}</b>
							</p>
							<p style="font-size: 14px;">
								Num Cuenta:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{nrocuetanegDL}}</b>
							</p>
							<p style="font-size: 14px;">
								Email:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{emailnegDL}}</b>
							</p>
							<p style="font-size: 14px;">
								Num Identificación:&nbsp;&nbsp;&nbsp;&nbsp;
								<b>{{nroidentificacionnegDL}}</b>
							</p>
							<br>
							
							
							<div ng-if="estatusnegDL == 3 && estatusnegBS == 3">
								<p>
									 <a href="">
									 	<img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
									 </a>
								</p>

								<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
									Confirmar Transferencia
								</button>
							</div>

							<div ng-if="estatusnegDL == 3 && estatusnegBS == 5">
								<p>
									<a href="{{ linkBS }}" target="_blank">
										<img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
									</a>
								</p>

								<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="autorizaTransf2();">
									Confirmar Transferencia
								</button>
							</div>


							<div ng-if="estatusnegDL == 5 && estatusnegBS == 5">
								<p>
									<a href="{{ linkBS }}" target="_blank">
										<img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
									</a>
								</p>
							</div>

					</div>
				</div>
			</div>
		</div>
		<!--SI EL ROL NO ES ADMINISTRADOR-->
		<div ng-if="tipousuario_idtipousuario != 5">
			<div style="margin-left: 140px;margin-top: 20px;">
				<div class="Table" >
					<div class="row nomargin nopadding" ng-controller="PostureCtrl" ng-init="montosXposturas();">


						<div class="cell" style="float: left">
							<div class="Tasa-de-Cambio" style="text-align: center;">
								Tasa de Cambio
								<div class="Bs-F-21300"> {{tasacambio | currency:"Bs F "}}</div>
							</div>
						</div>

						<div class="Cell" style="float: left">
							<div class="pile" >|</div>
						</div>

						<div class="Cell" style="float: left">
							<div class="Tasa-de-Cambio">
								Recibiras
								<div class="Bs-F-21300">{{simMonedaQuiero}}  {{recibire | currency:""}}</div>
							</div>
						</div>

						<div class="Cell" style="float: left">
							<div class="pile" >|</div>
						</div>

						<div class="Cell" style="float: left">
							<div class="Tasa-de-Cambio" >
								A Transferir
								<div class="Bs-F-21300">
									{{simMonedaTengo}} {{transferir | currency:""}}
								</div>
							</div>
						</div>

						<div class="Cell" style="float: left">
							<div class="pile" >|</div>
						</div>
						
						<div class="Cell" style="float: left">
							<div class="Tasa-de-Cambio" >
								Id Operaci&oacute;n
								<div class="Bs-F-21300">
									{{paramPost}}
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div class="Rectangle-2-Copy-4C" >
				<hr>
			</div>

			<div  class="Usuario-DH458">
				<div  class="text-style-1" >
					Usuario:&nbsp;{{username}}
				</div>	
			</div>
		
	<br>
		
		<div class="Table" style="margin-top: -10px;" >
			<div class="row nomargin nopadding" >

				<div class="Cell" ng-if="estatusNeg == 0 && userNeg=='no' &&  moneda == ''">
					<!--SE COMENTA PARA DESAPARECER EL CHAT-->
					<?php //include('../chat/chat.html'); ?>

					<?php include('../negociacion/negociacion.html'); ?>
				</div>	
				
				<div class="Cell" ng-if="estatusNeg == 1  && moneda == '' && userNeg==id">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>
						La contraparte está realizando la transferencia en Bs,<br> por favor espere a que la misma sea ejecutada.
					</div>
				</div>

				<div class="Cell" ng-if="estatusNeg == 2  && moneda == '' && userNeg==id">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>
						La contraparte está realizando la transferencia en Bs,<br> por favor espere a que la misma sea ejecutada.
					</div>
				</div>

				<div class="Cell" ng-if="estatusNeg == 2  && userNeg == id && moneda == 2">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>

						<h2>Datos de la Transferencia</h2>
						<br>
						<table class="table-responsive">
							<input type="hidden"  value="{{ idNeg }}" id="idNeg" name="idNeg">
							<tbody>
								<tr>
									<th>Banco:&nbsp;&nbsp; </th>
									<th>{{ banco }}</th>
								</tr>
								<tr>
									<th>Número de Cuenta:&nbsp;&nbsp; </th>
									<th>{{ nrocuenta }}</th>
								</tr>
								<tr>
									<th>Correo Electrónico:&nbsp;&nbsp; </th>
									<th>{{ email }}</th>
								</tr>
								<tr>
									<th>Nro Identificación:&nbsp;&nbsp; </th>
									<th>{{ nroidentificacion }}</th>
								</tr>
							</tbody>
						</table>
						<br>
						<input type = "file" name = "myFile"  id="myFile" />
						
						<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-6 col-md-6 col-sm-4 col-xs-12" onclick="evidenciaNeg(
							document.getElementById('idNeg').value,
							document.getElementById('myFile'));">
							Transferencia Realizada
						</button>

					</div>		
				</div>		
				<div class="Cell" ng-if="estatusNeg == 3 && userNeg == id && moneda == 2 ">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>
						La contraparte está revisando la información de la transferencia,<br> le informaremos cuando ya la haya confirmado.
					</div>
				</div>
				<div class="Cell" ng-if="estatusNeg == 3 && userNeg == id && moneda == '' ">
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-2 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-left">
						<br>
						La transferencia fue realizada, puede descargar el comprobante y confirmar la recepción de los fondos.
						
						<br><br><br>
					
							<a href="{{ comprobante }}" target="_blank">
								<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
										Descargar Comprobante
								</button>
							</a>
						<br><br>
						
							<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="confTransf2();">
									Confirmar Transferencia
							</button>
						

					</div>
				</div>
				
				<div class="Cell" ng-if="estatusNeg == 4 && userNeg == id && moneda == '' ">
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-2 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-left">
						<br>

						<h2>Datos de la Transferencia</h2>
						<br>
						<table class="table-responsive">
							<input type="hidden"  value="{{ idNeg }}" id="idNeg" name="idNeg">
							<input type="hidden"  value="{{ id }}" id="idUser" name="idUser">
							<tbody>
								<tr>
									<th>Banco:&nbsp;&nbsp;</th>
									<th>{{ banco }}</th>
								</tr>
								<tr>
									<th>Número de Cuenta:&nbsp;&nbsp; </th>
									<th>{{ nrocuenta }}</th>
								</tr>
								<tr>
									<th>Correo Electrónico:&nbsp;&nbsp; </th>
									<th>{{ email }}</th>
								</tr>
								<tr>
									<th>Nro Identificación:&nbsp;&nbsp; </th>
									<th>{{ nroidentificacion }}</th>
								</tr>
							</tbody>
						</table>
						<br>
						<input type = "file" name = "myFile"  id="myFile" />
						
						<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-6 col-md-6 col-sm-4 col-xs-12" onclick="evidenciaNegContraparte(
							document.getElementById('idUser').value,
							document.getElementById('idNeg').value,
							document.getElementById('myFile'));">
							Transferencia Realizada
						</button>

					</div>
				</div>
				<div class="Cell" ng-if="estatusNeg == 5 && userNeg == id && moneda == '' ">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>
						La contraparte está revisando la información de la transferencia,<br> le informaremos cuando ya la haya confirmado.
					</div>
				</div>

				<div class="Cell" ng-if="estatusNeg == 5 && userNeg == id && moneda == 2 ">
					<div class="col-lg-2 col-md-2 col-sm-3 col-xs-2 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-left">
						<br>
						La transferencia fue realizada, puede descargar el comprobante y confirmar la recepción de los fondos.
						
						<br><br><br>
					
							<a href="{{ comprobante }}" target="_blank">
								<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
										Descargar Comprobante
								</button>
							</a>
						<br><br>
						
							<button type="button" class="btn btn-secondary btn_orange_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="confTransf3();">
									Confirmar Transferencia
							</button>
						

					</div>
				</div>
				
				<div class="Cell" ng-if="estatusNeg == 6 && userNeg == id && moneda == 2 ">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>
						¡Felicitaciones¡ ya su cambio de divisas fue realizado, por favor confirme y no olvide calificar a su contraparte
					</div>
				</div>	

				<div class="Cell" ng-if="estatusNeg == 6 && userNeg == id && moneda == '' ">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 "></div>
					<div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
						<br>
						¡Felicitaciones¡ ya su cambio de divisas fue realizado, por favor confirme y no olvide calificar a su contraparte
					</div>
				</div>
									
			</div>
			
		</div>
	</div>

	<div class="row nomargin nopadding" >
				<div class="Cell" >
					<!--area tracking -->
					<?php include('../tracking/tracking.html'); ?>
				</div>
			</div>

	</div>

	<div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModaIInfOper" role="dialog">
                <div class="modal-dialog" title="">
                    <div class="modal-content doublemyinput" >
                        <div class="modal-header onlyheaderModal" >
                            <h4 class="modal-title text-center"></h4>
                        </div>
                        <div class="modal-body" >
                            <p>
                                ¡¡ Estimado usuario se informa que ya esta operaci&oacute;n ha sido concretada, intente crear una nueva !!
                            </p>
                        </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal"  >Aceptar</button>
                        </div>
                    </div>
                </div>
        </div>
		<!--modal de operacion cancelada-->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModaIInfOperCan" role="dialog">
                <div class="modal-dialog" title="">
                    <div class="modal-content doublemyinput" >
                        <div class="modal-header onlyheaderModal" >
                            <h4 class="modal-title text-center"></h4>
                        </div>
                        <div class="modal-body" >
                            <p>
                                ¡¡ Estimado usuario se informa que ya esta operaci&oacute;n ha sido cancelada por la contraparte!!
                            </p>
                        </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="redirecNewPostura();">Aceptar</button>
                        </div>
                    </div>
                </div>
        </div>
        <!--modal de operacion cancelada-->

        <!--modal de operacion denunciada-->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="myModaIInfOperDen" role="dialog">
                <div class="modal-dialog" title="">
                    <div class="modal-content doublemyinput" >
                        <div class="modal-header onlyheaderModal" >
                            <h4 class="modal-title text-center"></h4>
                        </div>
                        <div class="modal-body" >
                            <p>
                                ¡¡ Estimado usuario se informa que ya esta operaci&oacute;n ha sido denunciada por la contraparte!!
                            </p>
                        </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="redirecNewPostura();">Aceptar</button>
                        </div>
                    </div>
                </div>
        </div>
        <!--modal de operacion denunciada-->

</div>
        
</div>

