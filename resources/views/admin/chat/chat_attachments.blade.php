<form enctype="multipart/form-data">
    <input type="hidden" name="chat_id" value="{{$id}}">
    <input type="hidden" name="chat_origen" value="{{$origen}}">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="attachments" name="attachments[]" multiple>
        <label class="custom-file-label" for="attachments">Seleccione el/los archivo(s)</label>
    </div>
</form>
<ul class="list-group" id="listado-adjuntos">

</ul>
<script type="text/javascript" src="{{asset('js/chat/chat_attachments.js')}}"></script>