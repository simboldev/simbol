<div ng-if="username != undefined">
  <div>
    <h2 class="color_purple">Gesti&oacute;n de la Operaci&oacute;n</h2>
  </div>
  <!-- <div  class="row  index_posture_new_posture" class="container"  ng-controller="ChatCtrl" ng-init="refTrack();estatusOperacion();consEstatusNeg();"> -->
  <div  class="row  index_posture_new_posture" class="container"  ng-controller="ChatCtrl" ng-init="estatusOperacion();consEstatusNeg();">
    <div class="row nomargin nopadding index_posture_new_posture_tittle" class="col-md-12 col-sm-12 col-xs-12">
    <!--style="margin-top: 60px;margin-left: 130px;"-->
    <!--SI EL ROL ES ADMINISTRADOR-->
    <div ng-if="tipousuario_idtipousuario == 5" ng-init="verNegociacion()" >
      <div class="row nomargin nopadding" >
        <div class="Cell block-center" >
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="border-style:solid;height: 350px;">
            <table id="transferencia_bs" class="tg">
              <tr>
                <th class="tg-0pky" colspan="2"><span style="font-weight:700;font-style:normal">Datos Transferencia en Bolívares</span></th>
              </tr>
              <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
              </tr>
              <tr>
                <td class="tg-v47y">Banco</td>
                <td class="tg-qtf5">{{negociaciones.negociacion_bs.banco}}</td>
              </tr>
              <tr>
                <td class="tg-v47y">Nro. Cuenta</td>
                <td class="tg-qtf5">{{negociaciones.negociacion_bs.nrocuenta}}<br></td>
              </tr>
              <tr>
                <td class="tg-v47y">Correo</td>
                <td class="tg-qtf5">{{negociaciones.negociacion_bs.email}}</td>
              </tr>
              <tr>
                <td class="tg-v47y">Nro. Identificación</td>
                <td class="tg-qtf5">{{negociaciones.negociacion_bs.nroidentificacion}}</td>
              </tr>
            </table>
              <br>

              <!-- Confirma transf. En BS -->
              <div ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 2 && negociaciones.negociacion_bs.estatusNeg == 2">
                <p>
                  <a href="{{ negociaciones.negociacion_moneda_extranjera.comprobante }}" target="_blank">
                    <img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
                  </a>
                </p>
                <input type="hidden"  value="{{  negociaciones.negociacion_moneda_extranjera.idNeg }}" id="bo_id_negociacion_1">

                <input type="hidden"  value="{{ negociaciones.negociacion_bs.idNeg }}" id="bo_id_negociacion_contraparte_1">

                <button type="button" id="bo_btn_confirma_transf_bs" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="autorizaTransf(1,'bo_btn_confirma_transf_bs');" ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 2 && negociaciones.negociacion_bs.estatusNeg == 2 && negociaciones.negociacion_bs.estatus_autoriza_backoffice == 0">
                  Confirmar transferencia de {{negociaciones.negociacion_moneda_extranjera.usuario_nombre_usuario}}
                </button>
              </div>
              <!-- Confirma recepcion de BS -->
              <div ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 3 && negociaciones.negociacion_bs.estatusNeg == 3">
                <p>
                  <a href="{{ negociaciones.negociacion_moneda_extranjera.comprobante }}" target="_blank">
                    <img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
                  </a>
                </p>

              </div>
              <!-- Visualiza comprobantes -->
              <div ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 5 && negociaciones.negociacion_bs.estatusNeg == 5">
                <p>
                  <a href="{{ negociaciones.negociacion_moneda_extranjera.comprobante }}" target="_blank">
                    <img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
                  </a>
                </p>
              </div>

          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 " style="border-style:solid;height: 350px;">
              <table id="transferencia_bs" class="tg">
                <tr>
                  <th class="tg-0pky" colspan="2"><span style="font-weight:700;font-style:normal">Datos Transferencia en Dolares</span></th>
                </tr>
                <tr>
                  <td class="tg-0lax"></td>
                  <td class="tg-0lax"></td>
                </tr>
                <tr>
                  <td class="tg-v47y">Banco</td>
                  <td class="tg-qtf5">{{negociaciones.negociacion_moneda_extranjera.banco}}</td>
                </tr>
                <tr>
                  <td class="tg-0pky">ABA</td>
                  <td class="tg-0pky">{{negociaciones.negociacion_moneda_extranjera.aba}}</td>
                </tr>
                <tr>
                  <td class="tg-v47y">Nro. Cuenta</td>
                  <td class="tg-qtf5">{{negociaciones.negociacion_moneda_extranjera.nrocuenta}}</td>
                </tr>
                <tr>
                  <td class="tg-v47y">Correo</td>
                  <td class="tg-qtf5">{{negociaciones.negociacion_moneda_extranjera.email}}</td>
                </tr>
                <tr>
                  <td class="tg-v47y">Nro. Identificación</td>
                  <td class="tg-qtf5">{{negociaciones.negociacion_moneda_extranjera.nroidentificacion}}</td>
                </tr>
                <tr>
                  <td class="tg-v47y"></td>
                  <td class="tg-qtf5"></td>
                </tr>
              </table>
              <br>
              
              <!-- Confirma transf. En USD -->
              <div ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 3 && negociaciones.negociacion_bs.estatusNeg == 3">
                <p>
                   <a href="">
                    <img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
                   </a>
                </p>

                <button type="button" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                  Confirmar Transferencia
                </button>
              </div>
              <!-- Confirma recepcion de USD -->
              <div ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 4 && negociaciones.negociacion_bs.estatusNeg == 4">
                <p>
                  <a href="{{ negociaciones.negociacion_bs.comprobante }}" target="_blank">
                    <img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
                  </a>
                </p>
                
                <input type="hidden"  value="{{ negociaciones.negociacion_bs.idNeg }}" id="bo_id_negociacion_2">
                
                <input type="hidden"  value="{{ negociaciones.negociacion_moneda_extranjera.idNeg }}" id="bo_id_negociacion_contraparte_2">

                <button type="button" id="bo_btn_confirma_transf_usd" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="autorizaTransf(2,'bo_btn_confirma_transf_usd');" ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 4 && negociaciones.negociacion_bs.estatusNeg == 4 && negociaciones.negociacion_bs.estatus_autoriza_backoffice == 2">
                  Confirmar Transferencia
                </button>
              </div>


              <div ng-if="negociaciones.negociacion_moneda_extranjera.estatusNeg == 5 && negociaciones.negociacion_bs.estatusNeg == 5">
                <p>
                  <a href="{{ negociaciones.negociacion_bs.comprobante }}" target="_blank">
                    <img src="images/png/iconooxo.png" height="40%" width="40%" style="border-style:solid;" />
                  </a>
                </p>
              </div>

          </div>
        </div>
      </div>
    </div>

    <!--SI EL ROL NO ES ADMINISTRADOR-->
    <div ng-if="tipousuario_idtipousuario != 5" ng-controller="PostureCtrl" ng-init="montosXposturas();">
      <div style="margin-left: 140px;margin-top: 20px;">
        <div class="Table" >
          <div class="row nomargin nopadding">
            <div class="cell" style="float: left">
              <div class="Tasa-de-Cambio" style="text-align: center;">
                Tasa de Cambio
                <div class="Bs-F-21300"> {{tasacambio | currency:"BsS "}}</div>
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

<!--       <div class="Usuario-DH458">
        <div  class="text-style-1" >
          <p>Usuario:&nbsp;{{username}}</p>
        </div>  
      </div> -->
      <br>
      <div class="Table" style="margin-top: -10px;" >
        <div class="row nomargin nopadding" >
          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 0 && mi_negociacion.quiero_moneda == 0 && mi_negociacion.iduser==id">
            <!--SE COMENTA PARA DESAPARECER EL CHAT-->
            <?php //include('../chat/chat.html'); ?>
              <h3>Datos para transferencia</h3>
              <br>
              <h4>Agrega los datos de tu cuenta bancaria donde deseas recibir tus fondos</h4>
              <br>
            <?php include('../negociacion/negociacion.html'); ?>
          </div>

          <div class="Cell block-center" ng-if="mi_negociacion.estatusNeg == 1  && mi_negociacion.quiero_moneda == 1 && mi_negociacion.iduser == id && negociacion_contraparte_length == 0">
            <p>Esperamos a que la contraparte envíe los datos para que pueda transferirte.</p>
          </div>

          <!-- CASO 1 CONF. TRANSF. BS -->
          <!-- <div class="Cell" ng-if="mi_negociacion.estatusNeg == 1 && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 0 && mi_negociacion.iduser==id"> -->
          <div class="Cell" ng-if="estatus_neg_valido(1,mi_negociacion.estatusNeg) && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 0 && mi_negociacion.iduser==id">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <br>
              Tu contraparte está realizando la transferencia en {{simMonedaQuiero}}, por favor espera a que te confirmemos para que valides los fondos en tu banca online.
            </div>
          </div>

          <div class="Cell block-center" ng-if="mi_negociacion.estatusNeg == 1  && mi_negociacion.quiero_moneda == 2 && mi_negociacion.iduser == id && negociacion_contraparte_length == 0">
            <p>Esperamos a que tu contraparte envíe los datos para que puedas transferirle.</p>
          </div>

          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 1 && mi_negociacion.quiero_moneda == 2 && mi_negociacion.iduser == id && negociacion_contraparte_length > 0 ">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <b><p>Datos de la Transferencia</p></b>
              <br>
              <table class="table-responsive">
                <input type="hidden"  value="{{ mi_negociacion.idNeg }}" id="idNeg">
                <input type="hidden"  value="{{ mi_negociacion.iduser }}" id="idUser">
                <input type="hidden"  value="{{ negociacion_contraparte.idNeg }}" id="idNegContraparte">
                <input type="hidden"  value="{{mi_negociacion.estatusNeg+1}}" id="estatusNeg">
                <tbody>
                  <tr>
                    <th>Banco:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.banco }}</th>
                  </tr>
                  <tr>
                    <th>Número de Cuenta:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.nrocuenta }}</th>
                  </tr>
                  <tr>
                    <th>Correo Electrónico:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.email }}</th>
                  </tr>
                  <tr>
                    <th>Nro Identificación:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.nroidentificacion }}</th>
                  </tr>
                </tbody>
              </table>
              <br>
              <p>Una vez realices la transferencia sube tu comprobante en formato pdf o jpg para que podamos validarlo</p>
              <br>
              <input type = "file" name = "myFile"  id="myFile" onchange="mostrar_btn_transferencia_reliazada('myFile');" />
              <br>
              <button type="button" id="btn_transferecia_myFile" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-6 col-md-6 col-sm-4 col-xs-12" disabled onclick="evidenciaNeg(
                'btn_transferecia_myFile',
                document.getElementById('idUser').value,
                document.getElementById('idNeg').value,
                document.getElementById('myFile'),
                document.getElementById('idNegContraparte').value,
                document.getElementById('estatusNeg').value);">
                Ya transfer&iacute;
              </button>
            </div>    
          </div>

          <!-- CASO 2 CONF. RECIBI. BS -->
          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 2 && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 2 && mi_negociacion.iduser == id ">
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-2 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-left">
              <br>
              La transferencia fu&eacute; realizada, puedes descargar el comprobante y confirmar la recepción de los fondos.
              
              <br><br><br>
            
                <a href="{{ negociacion_contraparte.comprobante }}" target="_blank">
                  <button type="button" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                      Descargar Comprobante
                  </button>
                </a>
              <br><br>
              
              <button type="button" id="btn_confirma_transf_bs" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="confTransf('btn_confirma_transf_bs');" >
                  Confirmar Transferencia
              </button>
              

            </div>
          </div>
          <!-- monedaInternacional.indexOf(mi_negociacion.quiero_moneda) != -1 -->
          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 2  && mi_negociacion.quiero_moneda == 2 && estatus_neg_valido(3,mi_negociacion.estatus_autoriza_backoffice) && mi_negociacion.iduser == id">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <br>
              Tu contraparte está revisando la información de la transferencia, por favor espera a que te confirmemos para que valides los fondos en tu banca online.
            </div>
          </div>


          <!-- CASO 3 CONF. TRRANSF. MONEDA EXTRANJERA -->
          <!-- <div class="Cell" ng-if="mi_negociacion.estatusNeg == 3 && monedaInternacional.indexOf(mi_negociacion.quiero_moneda) !== -1 && mi_negociacion.iduser==id"> -->
          <div class="Cell" ng-if="estatus_neg_valido(2,mi_negociacion.estatusNeg) && mi_negociacion.quiero_moneda == 2 && negociacion_contraparte.estatus_autoriza_backoffice == 2 && mi_negociacion.iduser==id">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <br>
              Tu contraparte está realizando la transferencia en {{simMonedaQuiero}}, por favor espera a que te confirmemos para que valides los fondos en tu banca online.
            </div>
          </div>

          <!-- <div class="Cell" ng-if="mi_negociacion.estatusNeg == 3 && mi_negociacion.quiero_moneda == monedaBs[0] && mi_negociacion.iduser==id"> -->
          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 3  && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 2 && mi_negociacion.iduser == id && negociacion_contraparte_length > 0 ">
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-2 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-left">
              <h3>Datos para transferencia</h3>
              <br>
              <h4>Agrega los datos de tu cuenta bancaria donde deseas recibir tus fondos</h4>
              <br>
              <table class="table-responsive">
                <input type="hidden"  value="{{ mi_negociacion.idNeg }}" id="idNeg2">
                <input type="hidden"  value="{{ mi_negociacion.id }}" id="idUser2">
                <input type="hidden"  value="{{ negociacion_contraparte.idNeg }}" id="idNegContraparte2">
                <input type="hidden"  value="{{mi_negociacion.estatusNeg+1}}" id="estatusNeg2">
                <tbody>
                  <tr>
                    <th>Banco:&nbsp;&nbsp;</th>
                    <th>{{ negociacion_contraparte.banco }}</th>
                  </tr>
                  <tr>
                    <th>Número de Cuenta:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.nrocuenta }}</th>
                  </tr>
                  <tr>
                    <th>Correo Electrónico:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.email }}</th>
                  </tr>
                  <tr>
                    <th>Nro Identificación:&nbsp;&nbsp; </th>
                    <th>{{ negociacion_contraparte.nroidentificacion }}</th>
                  </tr>
                </tbody>
              </table>
              <br>
              <p>Una vez realices la transferencia sube tu comprobante en formato pdf o jpg para que podamos validarlo</p>
              <br>
              <input type = "file" id="myFile2" onchange="mostrar_btn_transferencia_reliazada('myFile2');"/>
              <br>
              <button type="button" id="btn_transferecia_myFile2" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-6 col-md-6 col-sm-4 col-xs-12" disabled onclick="evidenciaNeg(
                'btn_transferecia_myFile2',
                document.getElementById('idUser2').value,
                document.getElementById('idNeg2').value,
                document.getElementById('myFile2'),
                document.getElementById('idNegContraparte2').value,
                document.getElementById('estatusNeg2').value);">
                Ya transfer&iacute;
              </button>
            </div>
          </div>

          <!-- CASO 4 CONF. RECIBI. MONEDA EXTRANJERA -->
          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 4  && mi_negociacion.quiero_moneda == 2 && negociacion_contraparte.estatus_autoriza_backoffice == 4 && mi_negociacion.iduser == id">
            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-2 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-left">
              <br>
              La transferencia fu&eacute; realizada, puede descargar el comprobante y confirmar la recepción de los fondos.
              
              <br><br><br>
            
                <a href="{{ negociacion_contraparte.comprobante }}" target="_blank">
                  <button type="button" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                      Descargar Comprobante
                  </button>
                </a>
              <br><br>
              
                <button type="button" id="btn_confirma_transf_usd" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="confTransf('btn_confirma_transf_usd');">
                    Confirmar Transferencia
                </button>
            </div>
          </div>
          <!-- monedaInternacional.indexOf(mi_negociacion.quiero_moneda) != monedaBs[0] -->
          <div class="Cell" ng-if="mi_negociacion.estatusNeg == 4  && mi_negociacion.quiero_moneda == 1 && estatus_neg_valido(3,negociacion_contraparte.estatus_autoriza_backoffice) && mi_negociacion.iduser == id">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <br>
              Tu contraparte está revisando la información de la transferencia, por favor espera a que te confirmemos para que valides los fondos en tu banca online.
            </div>
          </div>

          <div class="Cell text-center" ng-if="mi_negociacion.estatusNeg == 5 && mi_negociacion.quiero_moneda == 1 && mi_negociacion.iduser == id ">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <br>
              ¡Felicitaciones! ya tu cambio fu&eacute; realizado.
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 col-md-offset-4 col-ms-offset-4">
              <button type="button" id="btn_go_home" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold col-md-4 col-sm-4 col-xs-12" ng-click="go_home()">
                  Ir al inicio
              </button>
            </div>
          </div>  

          <div class="Cell text-center" ng-if="mi_negociacion.estatusNeg == 5 && mi_negociacion.quiero_moneda == 2 && mi_negociacion.iduser == id ">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 "></div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 block-center">
              <br>
              ¡Felicitaciones! ya tu cambio fu&eacute; realizado.
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 col-md-offset-4 col-ms-offset-4">
              <button type="button" id="btn_go_home" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold col-md-4 col-sm-4 col-xs-12" ng-click="go_home()">
                  Ir al inicio
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row nomargin nopadding" >
      <div class="Cell" >
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
</div><!-- END ng-if="username != undefined" -->
