@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form>
    <h2>Información de la vigencia</h2>
    <div class="form-group">
        <label class="control-label" for="anio">Año</label>
        <input type="text" name="vigencia" class="form-control" value="{{$vigenciaActual->vigencia + 1}}" readonly required>
    </div>
    <div class="form-group">
        <label class="control-label" for="impedir_cambios">Impedir cambios al finalizar la vigencia?</label>
        {{ Form::select('impedir_cambios', ['SI'=>'SI', 'NO'=>'NO'], old('impedir_cambios'), ['id'=>'impedir_cambios', 'class'=>'form-control', 'required'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="inicio_vigencia">Fecha inicio vigencia</label>
        <input type="date" class="form-control datepicker" id="inicio_vigencia" name="inicio_vigencia" placeholder="Clic para establecer fecha" required value="{{old('inicio_vigencia')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="fin_vigencia">Fecha terminación vigencia</label>
        <input type="date" class="form-control datepicker" id="fin_vigencia" name="fin_vigencia" placeholder="Clic para establecer fecha" required  value="{{old('fin_vigencia')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="vigencia_salario_minimo">Salario mínimo</label>
        <input class="form-control" name="vigencia_salario_minimo" id="vigencia_salario_minimo" type="text" required>
    </div>
    <h2>Información de la empresa</h2>
    <div class="from-group">
        <label class="control-label" for="empresa_nombre">Nombre</label>
        <input class="form-control" name="empresa_nombre" id="empresa_nombre" type="text" required  value="{{old('empresa_nombre')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_descripcion">Descripción</label>
        <textarea class="form-control" name="empresa_descripcion" id="empresa_descricion"></textarea>
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_sigla">Sigla</label>
        <input class="form-control" name="empresa_sigla" id="empresa_sigla" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_direccion">Dirección</label>
        <input class="form-control" name="empresa_direccion" id="empresa_direccion" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_telefono">Teléfono</label>
        <input class="form-control" name="empresa_telefono" id="empresa_telefono" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_horario">Horario</label>
        <input class="form-control" name="empresa_horario" id="empresa_horario" type="text">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_web">Página web</label>
        <input class="form-control" name="empresa_web" id="empresa_web" type="text" required value="{{old('empresa_web')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_correo">Correo contacto</label>
        <input class="form-control" name="empresa_correo" id="empresa_correo" type="text" required value="{{old('empresa_correo')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_correo_administrador">Correo administrador</label>
        <input class="form-control" name="empresa_correo_administrador" id="empresa_correo_administrador" type="email" required value="{{old('empresa_correo_administrador')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_facebook">Facebook (username)</label>
        <input class="form-control" name="empresa_facebook" id="empresa_facebook" type="text" required value="{{old('empresa_facebook')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_twitter">Twitter (username)</label>
        <input class="form-control" name="empresa_twitter" id="empresa_twitter" type="text" required value="{{old('empresa_twitter')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_keywords">Keywords (SEO)</label>
        <input class="form-control" name="empresa_keywords" id="empresa_keywords" type="text" required value="{{old('empresa_keywords')}}">
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_map_coordinates">Coordenadas</label>
        <input class="form-control" name="empresa_map_coordinates" id="empresa_map_coordinates" type="text" required  value="{{old('empresa_map_coordinates')}}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="empresa_logo_menu" id="empresa_logo_menu" required value="{{old('empresa_logo_menu')}}">
        <label class="custom-file-label" for="empresa_logo_menu">Logo del menú</label>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="empresa_logo" id="empresa_logo" required value="{{old('empresa_logo')}}">
        <label class="custom-file-label" for="empresa_logo">Logo empresa</label>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="empresa_header" id="empresa_header" required value="{{old('empresa_header')}}">
        <label class="custom-file-label" for="empresa_header">Logo de encabezado</label>
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_nombre_director">Nombre director</label>
        <input class="form-control" name="empresa_nombre_director" id="empresa_nombre_director" type="text" required  value="{{old('empresa_nombre_director')}}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="empresa_firma_director" id="empresa_firma_director" required value="{{old('empresa_firma_director')}}">
        <label class="custom-file-label" for="empresa_firma_director">Firma director</label>
    </div>
    <div class="from-group">
        <label class="control-label" for="empresa_nombre_inspector">Nombre inspector</label>
        <input class="form-control" name="empresa_nombre_inspector" id="empresa_nombre_inspector" type="text" required value="{{old('empresa_firma_inspector')}}">
    </div>  
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="empresa_firma_inspector" id="empresa_firma_inspector">
        <label class="custom-file-label" for="empresa_firma_inspector">Cambiar firma inspector</label>
    </div>   
    <h2>Información de Gestión Documental</h2>
    <div class="form-group">
        <label class="control-label" for="gd_radicado_entrada">Consecutivo radicado entrada (Debe tener la longitud final. No debe ser mayor a seis caracteres. Puede empezar por ceros)</label>
        <input type="text" name="gd_radicado_entrada" id="gd_radicado_entrada" class="form-control" required  value="{{old('gd_radicado_entrada')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="pqr_radicado_salida">Consecutivo radicado salida (Debe tener la longitud final. No debe ser mayor a seis caracteres. Puede empezar por ceros)</label>
        <input type="text" name="gd_radicado_salida" id="gd_radicado_salida" class="form-control" required  value="{{old('gd_radicado_salida')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="pqr_sancion_consecutivo">Consecutivo sanciones (Debe tener la longitud final. No debe ser mayor a cuatro caracteres. Puede empezar por ceros)</label>
        <input type="text" name="gd_sancion_consecutivo" id="gd_sancion_consecutivo" class="form-control" required  value="{{old('gd_sancion_consecutivo')}}">
    </div>
    <h2>Información de PQR</h2>
    <div class="form-group">
        <label class="control-label" for="pqr_editar_resuelto">Permitir editar procesos resuelto?</label>
        {{ Form::select('pqr_editar_resuelto', ['SI'=>'SI', 'NO'=>'NO'], old('pqr_editar_resuelto'), ['id'=>'pqr_editar_resuelto', 'class'=>'form-control', 'required'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="pqr_previo_aviso">Días ciclo comprobación</label>
        <input type="number" name="pqr_previo_aviso" id="pqr_previo_aviso" class="form-control" required  value="{{old('pqr_previo_aviso')}}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="pqr_logo_radicado" id="pqr_logo_radicado" required value="{{old('pqr_logo_radicado')}}">
        <label class="custom-file-label" for="pqr_logo_radicado">Logo etiqueta radicado</label>
    </div>
    <h2>Información de trámites</h2>
    <div class="form-group">
        <label class="control-label" for="tramite_inicio_atencion">Hora inicio atención</label>
        <input type="date" class="form-control timepicker" id="tramite_inicio_atencion" name="tramite_inicio_atencion" placeholder="Clic para establecer hora" required  value="{{old('tramite_inicio_atencion')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="tramite_fin_atencion">Hora fin atención</label>
        <input type="date" class="form-control timepicker" id="tramite_fin_atencion" name="tramite_fin_atencion" placeholder="Clic para establecer hora" required  value="{{old('tramite_fin_atencion')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="tramite_habilita_turno_rellamado">Habilitar re-llamado de turnos?</label>
        {{ Form::select('tramite_habilita_turno_rellamado', ['SI'=>'SI', 'NO'=>'NO'], old('tramite_habilita_turno_rellamado'), ['id'=>'tramite_habilita_turno_rellamado', 'class'=>'form-control', 'required'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="tramite_habilita_turno_preferencial">Habilitar turnos preferenciales?</label>
        {{ Form::select('tramite_habilita_turno_preferencial', ['SI'=>'SI', 'NO'=>'NO'], old('tramite_habilita_turno_preferencial'), ['id'=>'tramite_habilita_turno_preferencial', 'class'=>'form-control', 'required'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="tramite_habilitar_turno_transferencia">Habilitar transferencia de turnos?</label>
        {{ Form::select('tramite_habilitar_turno_transferencia', ['SI'=>'SI', 'NO'=>'NO'], old('tramite_habilitar_turno_transferencia'), ['id'=>'tramite_habilitar_turno_transferencia', 'class'=>'form-control', 'required'])  }}
    </div>
    <div class="form-group">
        <label class="control-label" for="tramite_tiempo_espera_turno">Tiempo de espera turno</label>
        <input type="number" name="tramite_tiempo_espera_turno" id="tramite_tiempo_espera_turno" class="form-control" required  value="{{old('tramite_tiempo_espera_turno')}}">
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="tramite_logo_turno" id="tramite_logo_turno" required value="{{old('tramite_logo_turno')}}">
        <label class="custom-file-label" for="tramite_logo_turno">Logo etiqueta</label>
    </div>
    <h2>Información de tarjeta de operaciones</h2>
    <div class="from-group">
        <label class="control-label" for="to_consecutivo_inicial">Consecutivo inicial</label>
        <input type="text" class="form-control" name="to_consecutivo_inicial" id="to_consecutivo_inicial" required>
    </div>
    <div class="from-group">
        <label class="control-label" for="to_marca_agua">Marca de agua</label>
        <input type="text" class="form-control" name="to_marca_agua" id="to_marca_agua" required>
    </div>
    <div class="from-group">
        <label class="control-label" for="to_valor_unitario">Valor unitario</label>
        <input type="text" class="form-control" name="to_valor_unitario" id="to_valor_unitario" required>
    </div>
    <h4>Imágen encabezado</h4>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="to_imagen_encabezado" id="to_imagen_encabezado" required>
        <label class="custom-file-label" for="to_imagen_encabezado">Imagen de encabezado</label>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/parametros/vigencias/nuevoRegistro.js')}}"></script>