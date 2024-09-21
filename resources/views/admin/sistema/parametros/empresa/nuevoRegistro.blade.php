<form enctype="multipart/form-data">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, null, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="from-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input class="form-control" name="nombre" id="nombre" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="descripcion">Descripción</label>
        <textarea class="form-control" name="descripcion" id="descricion"></textarea>
    </div>
    <div class="from-group">
        <label class="control-label" for="sigla">Sigla</label>
        <input class="form-control" name="sigla" id="sigla" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="direccion">Dirección</label>
        <input class="form-control" name="direccion" id="direccion" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="telefono">Teléfono</label>
        <input class="form-control" name="telefono" id="telefono" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="horario">Horario</label>
        <input class="form-control" name="horario" id="horario" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="web">Página web</label>
        <input class="form-control" name="web" id="web" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="correo">Correo contacto</label>
        <input class="form-control" name="correo" id="correo" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="correo_administrador">Correo administrador</label>
        <input class="form-control" name="correo_administrador" id="correo_administrador" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="facebook">Facebook (username)</label>
        <input class="form-control" name="facebook" id="facebook" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="twitter">Twitter (username)</label>
        <input class="form-control" name="twitter" id="twitter" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="keywords">Keywords (SEO)</label>
        <input class="form-control" name="keywords" id="keywords" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="map_coordinates">Coordenadas</label>
        <input class="form-control" name="map_coordinates" id="map_coordinates" type="text">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo_menu" id="logo_menu">
        <label class="custom-file-label" for="logo_menu">Logo del menú</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_logo_menu" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo" id="logo">
        <label class="custom-file-label" for="logo">Logo empresa</label>
    </div>
    <hr>
    Previsualización<br>
    <iframe id="viewer_logo" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="header" id="header">
        <label class="custom-file-label" for="header">Logo de encabezado</label>
    </div>
    <hr>
    Previsualización<br>
    <iframe id="viewer_header" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <div class="from-group">
        <label class="control-label" for="nombre_director">Nombre director</label>
        <input class="form-control" name="nombre_director" id="nombre_director" type="text">
    </div>    
    <hr>
    Previsualización<br>
    <iframe id="viewer_firma" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="firma_director" id="firma_director">
        <label class="custom-file-label" for="firma_director">Cambiar firma director</label>
    </div>
    <hr>
    <div class="from-group">
        <label class="control-label" for="nombre_inspector">Nombre inspector</label>
        <input class="form-control" name="nombre_inspector" id="nombre_inspector" type="text">
    </div>    
    <hr>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="firma_inspector" id="firma_inspector">
        <label class="custom-file-label" for="firma_inspector">Cambiar firma inspector</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_firma_inspector" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/sistema/parametros/empresa/nuevoRegistro.js')}}"></script>