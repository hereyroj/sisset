<div class="panel panel-default">
   <div class="card-body">
        <h4>Radicado {{$tipoRadicado}}</h4>
        <hr>
        <div style="text-align: center; font-weight: bold; font-size: 16px;">
            {{$numeroRadicado}}
        </div>
        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="imprimirRadicado('{{$tipoRadicado}}','{{$idRadicado}}');">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir radicado
        </button>
    </div>
</div>
<div class="panel panel-default">
   <div class="card-body">
        <h4>Documento del radicado</h4>
        <hr>
        <form enctype="multipart/form-data" id="frmUploadFile">
            <input type="hidden" name="idPqr" id="idPqr" value="{{$idPqr}}">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file" id="file" required data-parsley-max-file-size="51200" data-parsley-fileextension="pdf">
                    <label class="custom-file-label" for="file">Archivo escaneado con sticker de radicación</label>
                </div>
                Previsualización<br>
            <iframe style="margin-bottom:20px; width: 100%;" id="viewer" frameborder="0" scrolling="no" height="700"></iframe>
            <button type="button" class="btn btn-success btn-lg btn-block" onclick="uploadFileRadicado();">
                <span class="glyphicon glyphicon-open" aria-hidden="true"></span> Subir archivo
            </button>
        </form>        
    </div>
</div>
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/uploadFileRadicado.js')}}"></script>