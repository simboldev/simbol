<div ng-if="username != undefined ">
  <div ng-controller="ChatCtrl" ng-init="estatusOperacion();consEstatusNeg();">
    <div class="row nomargin nopadding tittle_gral">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2 class="color_purple">GESTIÓN DE LA OPERACIÓN</h2>
      </div>
      <div ng-if="tipousuario_idtipousuario == 5" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        ID: {{paramPost}}
      </div>
    </div>
    <div class="row nomargin nopadding index_posture_my_postures" class="container">
      <!--SI EL ROL ES ADMINISTRADOR-->
      <div ng-if="tipousuario_idtipousuario == 5" ng-init="verNegociacion()" ng-init="montosXposturas();" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
        <div class="row nomargin nopadding">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 
            border_right_gray_simbol nopadding_left">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="color_purple">
                  Datos Transferencia en {{txt_bs}}
                </h4>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Banco:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_bs.banco}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Número de cuenta:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_bs.nrocuenta}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Correo:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_bs.email}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Número de identificación:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_bs.nroidentificacion}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Comprobante:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="color_purple">
                  Datos Transferencia en {{txt_usd}}
                </h4>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Banco:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_moneda_extranjera.banco}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    ABA:
                  </p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_moneda_extranjera.aba}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Número de cuenta:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_moneda_extranjera.nrocuenta}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Correo:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_moneda_extranjera.email}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Número de identificación:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p>
                    {{negociaciones.negociacion_moneda_extranjera.nroidentificacion}}
                  </p>
                </div>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <p class="font_weight_bold">
                    Comprobante:
                  </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
        </div>
      </div>
      <!--SI EL ROL NO ES ADMINISTRADOR-->
      <div ng-if="tipousuario_idtipousuario != 5" ng-controller="PostureCtrl" ng-init="montosXposturas();" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row nopadding margin_tb_20">
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 border_right_gray_simbol">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              <label class="font_weight_bold color_purple">
                Tasa de Cambio
              </label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              {{tasacambio | number:2}} {{simMonedaTengo}}
            </div>
          </div>
          <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 border_right_gray_simbol">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              <label class="font_weight_bold color_purple">
                Tengo
              </label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              {{posture_user.tengo | number:2}} {{posture_user.tengo_moneda_simbolo}}
            </div>
          </div> -->
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 border_right_gray_simbol">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              <label class="font_weight_bold color_purple">
                Recibiras
              </label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              {{recibire | number:2}} {{simMonedaQuiero}}
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              <label class="font_weight_bold color_purple">
                A Transferir
              </label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              {{transferir | number:2}} {{simMonedaTengo}}
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 border_left_gray_simbol">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              <label class="font_weight_bold color_purple">
                Ip Operación
              </label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
              {{paramPost}}
            </div>
          </div>
        </div>
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

          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12  text-center" ng-if="mi_negociacion.estatusNeg == 1  && mi_negociacion.quiero_moneda == 1 && mi_negociacion.iduser == id && negociacion_contraparte_length == 0">
            <p>Esperamos a que la contraparte envíe los datos para que pueda transferirte.</p>
          </div>

          <!-- CASO 1 CONF. TRANSF. BS -->
          <!-- <div class="Cell" ng-if="mi_negociacion.estatusNeg == 1 && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 0 && mi_negociacion.iduser==id"> -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12  text-center" ng-if="estatus_neg_valido(1,mi_negociacion.estatusNeg) && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 0 && mi_negociacion.iduser==id">
            <p>Tu contraparte está realizando la transferencia en {{simMonedaQuiero}}, por favor espera a que te confirmemos para que valides los fondos en tu banca online.</p>
          </div>

          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12  text-center" ng-if="mi_negociacion.estatusNeg == 1  && mi_negociacion.quiero_moneda == 2 && mi_negociacion.iduser == id && negociacion_contraparte_length == 0">
            <p>Esperamos a que tu contraparte envíe los datos para que puedas transferirle.</p>
          </div>

          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12 margin_top_20" ng-if="mi_negociacion.estatusNeg == 1 && mi_negociacion.quiero_moneda == 2 && mi_negociacion.iduser == id && negociacion_contraparte_length > 0">
            <div class="col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <h4 class="color_purple">
                Transfiere a tu contraparte
              </h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <p class="font_weight_bold">
                Datos de la Transferencia
              </p>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Banco:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.banco}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Número de Cuenta:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.nrocuenta}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Correo Electrónico:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.email}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Nro Identificación:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.nroidentificacion}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p>
                  Una vez realices la transferencia sube tu comprobante en formato pdf o jpg para que podamos validarlo.
                </p>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden"  value="{{ mi_negociacion.idNeg }}" id="idNeg">
                <input type="hidden"  value="{{ mi_negociacion.iduser }}" id="idUser">
                <input type="hidden"  value="{{ negociacion_contraparte.idNeg }}" id="idNegContraparte">
                <input type="hidden"  value="{{mi_negociacion.estatusNeg+1}}" id="estatusNeg">
                <input type ="file" name = "myFile"  id="myFile" onchange="mostrar_btn_transferencia_reliazada('myFile');" />
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin_tb_20 text-center">
                <button type="button" id="btn_transferecia_myFile" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-offset-3 col-md-offset-3 col-sm-offset-3 col-lg-6 col-md-6 col-sm-4 col-xs-12" disabled onclick="evidenciaNeg(
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
          </div>

          <!-- CASO 2 CONF. RECIBI. BS -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12  text-center margin_top_20" ng-if="mi_negociacion.estatusNeg == 2 && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 2 && mi_negociacion.iduser == id ">
            <div class="col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <p>
                La transferencia fué realizada, puedes descargar el comprobante y confirmar la recepción de los fondos.
              </p>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 margin_top_20">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <a href="{{ negociacion_contraparte.comprobante }}" target="_blank">
                  <button type="button" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    Descargar Comprobante
                  </button>
                </a>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="button" id="btn_confirma_transf_bs" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="confTransf('btn_confirma_transf_bs');" >
                  Confirmar Transferencia
                </button>
              </div>
            </div>
          </div>
          <!-- monedaInternacional.indexOf(mi_negociacion.quiero_moneda) != -1 -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12 text-center margin_top_20" ng-if="mi_negociacion.estatusNeg == 2  && mi_negociacion.quiero_moneda == 2 && estatus_neg_valido(3,mi_negociacion.estatus_autoriza_backoffice) && mi_negociacion.iduser == id">
            <div class="col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <p>
                Tu contraparte está revisando la información de la transferencia, por favor espera a que te confirmemos para que valides los fondos en tu banca online.
              </p>
            </div>
          </div>
          <!-- CASO 3 CONF. TRRANSF. MONEDA EXTRANJERA -->
          <!-- <div class="Cell" ng-if="mi_negociacion.estatusNeg == 3 && monedaInternacional.indexOf(mi_negociacion.quiero_moneda) !== -1 && mi_negociacion.iduser==id"> -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12  text-center" ng-if="estatus_neg_valido(2,mi_negociacion.estatusNeg) && mi_negociacion.quiero_moneda == 2 && negociacion_contraparte.estatus_autoriza_backoffice == 2 && mi_negociacion.iduser==id"><br>
            <p>Tu contraparte está realizando la transferencia en {{simMonedaQuiero}}, por favor espera a que te confirmemos para que valides los fondos en tu banca online.</p>
          </div>
          <!-- <div class="Cell" ng-if="mi_negociacion.estatusNeg == 3 && mi_negociacion.quiero_moneda == monedaBs[0] && mi_negociacion.iduser==id"> -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12 margin_top_20" ng-if="mi_negociacion.estatusNeg == 3  && mi_negociacion.quiero_moneda == 1 && negociacion_contraparte.estatus_autoriza_backoffice == 2 && mi_negociacion.iduser == id && negociacion_contraparte_length > 0 ">
            <div class="col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <h4 class="color_purple">
                Transfiere a tu contraparte
              </h4>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <p class="font_weight_bold">
                Datos de la Transferencia
              </p>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Banco:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.banco}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  ABA:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.aba}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Número de Cuenta:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.nrocuenta}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Correo Electrónico:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.email}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="font_weight_bold">
                  Nro Identificación:
                </p>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p>
                  {{negociacion_contraparte.nroidentificacion}}
                </p>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p>
                  Una vez realices la transferencia sube tu comprobante en formato pdf o jpg para que podamos validarlo.
                </p>
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden"  value="{{ mi_negociacion.idNeg }}" id="idNeg2">
                <input type="hidden"  value="{{ mi_negociacion.id }}" id="idUser2">
                <input type="hidden"  value="{{ negociacion_contraparte.idNeg }}" id="idNegContraparte2">
                <input type="hidden"  value="{{mi_negociacion.estatusNeg+1}}" id="estatusNeg2">
                <input type = "file" id="myFile2" onchange="mostrar_btn_transferencia_reliazada('myFile2');"/>
              </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin_tb_20 text-center">
                <button type="button" id="btn_transferecia_myFile2" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-offset-3 col-md-offset-3 col-sm-offset-3 col-lg-6 col-md-6 col-sm-4 col-xs-12" disabled onclick="evidenciaNeg(
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
          </div>
          <!-- CASO 4 CONF. RECIBI. MONEDA EXTRANJERA -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12  text-center margin_top_20" ng-if="mi_negociacion.estatusNeg == 4  && mi_negociacion.quiero_moneda == 2 && negociacion_contraparte.estatus_autoriza_backoffice == 4 && mi_negociacion.iduser == id">
            <div class="col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <p>
                La transferencia fué realizada, puedes descargar el comprobante y confirmar la recepción de los fondos.
              </p>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 margin_top_20">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <a href="{{ negociacion_contraparte.comprobante }}" target="_blank">
                  <button type="button" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                      Descargar Comprobante
                  </button>
                </a>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="button" id="btn_confirma_transf_usd" class="btn btn-secondary btn_green_simbol pull-center font_weight_bold col-lg-12 col-md-12 col-sm-12 col-xs-12" ng-click="confTransf('btn_confirma_transf_usd');">
                  Confirmar Transferencia
                </button>
              </div>
            </div>
          </div>
          <!-- monedaInternacional.indexOf(mi_negociacion.quiero_moneda) != monedaBs[0] -->
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12 margin_top_20" ng-if="mi_negociacion.estatusNeg == 4  && mi_negociacion.quiero_moneda == 1 && estatus_neg_valido(3,negociacion_contraparte.estatus_autoriza_backoffice) && mi_negociacion.iduser == id">
            <div class="col-md-12 col-sm-12 col-xs-12 margin_tb_20">
              <p>
                Tu contraparte está revisando la información de la transferencia, por favor espera a que te confirmemos para que valides los fondos en tu banca online.
              </p>
            </div>
          </div>
          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12 text-center" ng-if="mi_negociacion.estatusNeg == 5 && mi_negociacion.quiero_moneda == 1 && mi_negociacion.iduser == id ">
            <br>
            <p>¡Felicitaciones! ya tu cambio fu&eacute; realizado.</p>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 col-md-offset-4 col-ms-offset-4">
              <button type="button" id="btn_go_home" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold margin_top_20 col-md-4 col-sm-4 col-xs-12" ng-click="go_home()">
                Ir al inicio
              </button>
            </div>
          </div>  

          <div class="col-md-offset-1 col-sm-offset-1 col-md-10 col-sm-10 col-xs-12 text-center" ng-if="mi_negociacion.estatusNeg == 5 && mi_negociacion.quiero_moneda == 2 && mi_negociacion.iduser == id ">
            <br>
            <p>¡Felicitaciones! ya tu cambio fu&eacute; realizado.</p>
            <div class="col-lg-8 col-md-8 col-sm-8col-xs-8 col-md-offset-4 col-ms-offset-4">
              <button type="button" id="btn_go_home" class="btn btn-secondary btn_purple_simbol pull-center font_weight_bold margin_top_20 col-md-4 col-sm-4 col-xs-12" ng-click="go_home()">
                Ir al inicio
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="row nomargin nopadding" >
        <div class="Cell" >
          <?php // include('../tracking/tracking.html'); ?>
        </div>
      </div> -->
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
