<form role="form" enctype="multipart/form-data">
    <div class="form-group">
        <label for="publicationDate" class="control-label">Fecha de publicación</label>
        <input type="date" class="form-control datepicker" id="publicationDate" name="publicationDate" placeholder="Clic para establecer fecha">
    </div>
    <div class="form-group">
        <label for="cc" class="control-label">Cedula</label>
        <input id="cc" type="text" class="form-control" name="cc">
    </div>
    <div class="form-group">
        <label for="name" class="control-label">Nombre</label>
        <input id="name" type="text" class="form-control" name="name">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="edicto" name="edicto">
        <label class="custom-file-label" for="edicto">Edicto</label>
    </div>
    Previsualización<br>
    <iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/coactivo/comparendos/nuevaNotificacion.js')}}"></script>