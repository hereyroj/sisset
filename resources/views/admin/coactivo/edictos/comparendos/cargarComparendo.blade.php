<form role="form" enctype="multipart/form-data">
    <input type="hidden" name="comparendoId" value="{{$comparendo->id}}" name="comparendoId">
    <div class="form-group">
        <label for="publicationDateEdit" class="control-label">Fecha de publicación</label>
        <input type="date" class="form-control datepicker" id="publicationDateEdit" name="publicationDateEdit"  value="{{$comparendo->publication_date}}">
    </div>
    <div class="form-group">
        <label for="comparendoCc" class="control-label">Cedula</label>
        <input id="comparendoCc" type="text" class="form-control" name="comparendoCc" value="{{ $comparendo->cc }}">
    </div>
    <div class="form-group">
        <label for="comparendoName" class="control-label">Nombre</label>
        <input id="comparendoName" type="text" class="form-control" name="comparendoName" value="{{ $comparendo->name }}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="comparendoEdicto" name="comparendoEdicto">
        <label class="custom-file-label" for="comparendoEdicto">Edicto</label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/comparendos/cargarComparendo.js')}}"></script>