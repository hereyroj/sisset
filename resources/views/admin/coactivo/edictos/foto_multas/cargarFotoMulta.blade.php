<form role="form" enctype="multipart/form-data">
    <input type="hidden" name="fotoMultaId" value="{{$fotoMulta->id}}">
    <div class="form-group">
        <label for="publicationDateEdit" class="control-label">Fecha de publicación</label>
        <input type="date" class="form-control datepicker" id="publicationDateEdit" name="publicationDateEdit" value="{{$fotoMulta->publication_date}}">
    </div>
    <div class="form-group">
        <label for="fotoMultaCc" class="control-label">Cedula</label>
        <input id="fotoMultaCc" type="text" class="form-control" name="fotoMultaCc" value="{{ $fotoMulta->cc }}">
    </div>
    <div class="form-group">
        <label for="fotoMultaName" class="control-label">Nombre</label>
        <input id="fotoMultaName" type="text" class="form-control" name="fotoMultaName" value="{{ $fotoMulta->name }}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="fotoMultaEdicto" name="fotoMultaEdicto">
        <label class="custom-file-label" for="fotoMultaEdicto">Edicto</label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/fotomultas/cargarFotoMulta.js')}}"></script>