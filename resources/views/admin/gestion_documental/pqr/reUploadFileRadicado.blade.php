<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$id}}">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="archivo" id="archivo" required data-parsley-max-file-size="51200" data-parsley-fileextension="pdf">
        <label class="custom-file-label" for="archivo">Archivo(PDF)</label>
    </div>
    Previsualización<br>
    <iframe style="margin-bottom:20px; width: 100%;" id="viewer" frameborder="0" scrolling="no" height="700"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/reUploadFileRadicado.js')}}"></script>