<div ng-if="username != undefined ">
	<div >
		<h2>Gesti&oacute;n de la Operaci&oacute;n</h2>
	</div>
	<div  class="row  index_posture_new_posture" class="container"  ng-controller="ChatCtrl" ng-init="refTrack();estatusOperacion();">
		
		<div class="row nomargin nopadding index_posture_new_posture_tittle" class="col-md-12 col-sm-12 col-xs-12">
			<!--style="margin-top: 60px;margin-left: 130px;"-->
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
		</div>

		<div class="Table" style="margin-top: -10px;">
			<div class="row nomargin nopadding" >
				<div class="Cell" >
					<?php include('../chat/chat.html'); ?>
				</div>	
									
			</div>
		</div>

		<div class="Table" style="margin-top: -20px;">
			<div class="row nomargin nopadding" >
				<div class="Cell" >
					<!--area tracking -->
					<?php include('../tracking/tracking.html'); ?>
				</div>
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

