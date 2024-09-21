<form enctype="multipart/form-data">
    <input type="hidden" name="registro_id" value="{{$registro->id}}">
    <div class="from-group">
        <label class="control-label" for="vigencia">Vigencia</label>
        {{ Form::select('vigencia', $vigencias, $registro->vigencia_id, ['id'=>'vigencia', 'class'=>'form-control'])  }}
    </div>
    <div class="from-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input class="form-control" name="nombre" id="nombre" type="text" value="{{$registro->empresa_nombre}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="descripcion">Descripción</label>
        <textarea class="form-control" name="descripcion" id="descricion">{{$registro->descripcion}}</textarea>
    </div>
    <div class="from-group">
        <label class="control-label" for="sigla">Sigla</label>
        <input class="form-control" name="sigla" id="sigla" type="text" value="{{$registro->empresa_sigla}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="direccion">Dirección</label>
        <input class="form-control" name="direccion" id="direccion" type="text" value="{{$registro->empresa_direccion}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="telefono">Teléfono</label>
        <input class="form-control" name="telefono" id="telefono" type="text" value="{{$registro->empresa_telefono}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="horario">Horario</label>
        <input class="form-control" name="horario" id="horario" type="text" value="{{$registro->horario}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="web">Página web</label>
        <input class="form-control" name="web" id="web" type="text" value="{{$registro->empresa_web}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="correo">Correo contacto</label>
        <input class="form-control" name="correo" id="correo" type="text" value="{{$registro->empresa_correo_contacto}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="correo_administrador">Correo administrador</label>
        <input class="form-control" name="correo_administrador" id="correo_administrador" type="text" value="{{$registro->correo_administrador}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="facebook">Facebook (username)</label>
        <input class="form-control" name="facebook" id="facebook" type="text" value="{{$registro->facebook}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="twitter">Twitter (username)</label>
        <input class="form-control" name="twitter" id="twitter" type="text" value="{{$registro->twitter}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="keywords">Keywords (SEO)</label>
        <input class="form-control" name="keywords" id="keywords" type="text" value="{{$registro->keywords}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="map_coordinates">Coordenadas</label>
        <input class="form-control" name="map_coordinates" id="map_coordinates" type="text" value="{{$registro->empresa_map_coordinates}}">
    </div>
    <h4>Logo del menú</h4>
    <img src="{{asset('storage/parametros/empresa/'.$registro->empresa_logo_menu)}}" class="img-thumbnail">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo_menu" id="logo_menu">
        <label class="custom-file-label" for="logo_menu">Cambiar logo del menú</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_logo_menu" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <hr>
    <h4>Logo de la empresa</h4>
    <img src="{{asset('storage/parametros/empresa/'.$registro->empresa_logo)}}" class="img-thumbnail">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="logo" id="logo">
        <label class="custom-file-label" for="logo">Cambiar logo empresa</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_logo" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <hr>
    <h4>Logo de encabezado</h4>
    <img src="{{asset('storage/parametros/empresa/'.$registro->empresa_header)}}" class="img-thumbnail">
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="header" id="header">
        <label class="custom-file-label" for="header">Cambiar logo de encabezado</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_header" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <hr>
    <h4>Firma director</h4>
    <img src="{{url('admin/sistema/parametros/empresa/obtenerFirma/'.$registro->id)}}" class="img-thumbnail">
    <div class="from-group">
        <label class="control-label" for="nombre_director">Nombre director</label>
        <input class="form-control" name="nombre_director" id="nombre_director" type="text" value="{{$registro->nombre_director}}">
        <hr>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="firma_director" id="firma_director">
        <label class="custom-file-label" for="firma_director">Cambiar firma director</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_firma" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
    <hr>
    <h4>Firma inspector</h4>
    <img src="{{url('admin/sistema/parametros/empresa/obtenerFirmaInspector/'.$registro->id)}}" class="img-thumbnail">
    <div class="from-group">
        <label class="control-label" for="nombre_inspector">Nombre inspector</label>
        <input class="form-control" name="nombre_inspector" id="nombre_inspector" type="text" value="{{$registro->nombre_inspector}}">
    </div>    
    <hr>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="firma_inspector" id="firma_inspector">
        <label class="custom-file-label" for="firma_inspector">Cambiar firma inspector</label>
    </div>
    Previsualización<br>
    <iframe id="viewer_firma_inspector" frameborder="0" scrolling="no" width="100%" height="400"></iframe>
</form>
<script type="text/javascript" src="{{asset('js/sistema/parametros/empresa/editarRegistro.js')}}"></script>