
<style type="text/css">
        .chat{
                    list-style: none;
                    margin: 0;
                    padding: 0;
        }

        .chat li
        {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #B3A9A9;
        }

        .chat li.left .chat-body
        {
            margin-left: 60px;
        }

        .chat li.right .chat-body
        {
            margin-right: 60px;
        }


        .chat li .chat-body p
        {
            margin: 0;
            color: #777777;
        }

        .panel .slidedown .glyphicon, .chat .glyphicon
        {
            margin-right: 5px;
        }

        .panel-body
        {
            overflow-y: scroll;
            height: 250px;
        }

        ::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar
        {
            width: 12px;
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }

</style>
<form role="form" name="envChat" >
    <div class="container" style="width:980px;margin-left: -10px;" ng-controller="ChatCtrl">
            <div class="row">
                <div class="col-md-5">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-comment"></span> Chat
                        </div>
                        <div class="panel-body">
                            
                        </div>
                        <div class="panel-footer">
                            <div class="input-group">
                                <input type="hidden" name="user1" id="user1" value="1" ng-model="user1">
                                <input type="hidden" name="user2" id="user2" value="2" ng-model="user2">

                                <input id="btn-input" name="textouser1" type="text" class="form-control input-sm" placeholder="Escriba su mensaje..."  ng-model="textouser1" value="">
                                
                                <input id="adjuntouser1" name="adjuntouser1" type="hidden" class="form-control input-sm" placeholder="Escriba su mensaje..."  ng-model="adjuntouser1" value="">

                                <input type="hidden" name="textouser2" id="textouser2" ng-model="textouser2" placeholder="Escriba su mensaje..." class="form-control input-sm" value="">

                                <input id="adjuntouser2" name="adjuntouser2" type="hidden" class="form-control input-sm" placeholder="Escriba su mensaje..."  ng-model="adjuntouser2" value="">    

                                <input type="hidden" name="idposturasMatch" id="idposturasMatch" value="1" ng-model="idposturasMatch" 
                                >

                                <span class="input-group-btn">
                                    <button class="btn btn-info btn-sm" id="btn-chat" ng-click="submit($parent,envChat)" >
                                        Enviar
                                    </button>
                                    <button class="btn btn-warning btn-sm" id="btn-chat">
                                        Adjuntar</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</form>
