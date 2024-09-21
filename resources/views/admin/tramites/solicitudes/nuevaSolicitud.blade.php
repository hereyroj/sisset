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
    <div class="row">
        <div class="col-lg-8">
            <h3>Información del solicitante</h3>
            <div class="form-group">
                <label class="control-label" for="tipo_documento">Tipo documento</label> 
                {!! Form::select('tipo_documento', $tiposDocumentos, old('tipo_documento'), ['class' => 'form-control', 'id'=>'tipo_documento', 'required']) !!}
            </div>
            <div class="form-group">
                <label for="numero_documento" class="control-label">Numero</label>
                <input type="text" id="numero_documento" name="numero_documento" class="form-control" value="{{old('numero_documento')}}" required>
            </div>
            <div class="form-group">
                <label for="nombre" class="control-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{old('nombre')}}" required>
            </div>
            <div class="form-group">
                <label for="telefono" class="control-label">Teléfono</label>
                <input type="text" id="telefono" name="telefono" class="form-control" value="{{old('telefono')}}">
            </div>
            <div class="form-group">
                <label for="correo" class="control-label">Correo electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control"value="{{old('correo')}}">
            </div>
            <h3>Información de la solicitud</h3>
            <div class="form-group">
                <label class="control-label" for="servicios">Cantidad de servicios</label>
                <input type="number" name="servicios" min="1" class="form-control" value="{{old('servicios')}}" required>
            </div>
            <div class="form-group">
                <label class="control-label" for="tramite_solicitud_origen">Origen de la solicitud</label> 
                {!! Form::select('tramite_solicitud_origen', $tramitesSolicitudOrigenes, old('tramite_solicitud_origen'), ['id'=>'tramite_solicitud_origen', 'class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                <label class="control-label" for="preferente">Preferente</label> 
                {!! Form::select('preferente', ['0'=>'NO','1'=>'SI'], old('preferente'), ['id'=>'preferente', 'class'=>'form-control', 'required']) !!}
            </div>                    
            <div class="form-group">
                <label class="control-label" for="observacion">Observación</label>
                <textarea name="observacion" class="form-control" rows="7">{{old('observacion')}}</textarea>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label class="control-label" for="grupo">Grupo de tramites</label> 
                {!! Form::select('grupo', $tramitesGrupos, old('grupo'), ['id'=>'grupo', 'class'=>'form-control', 'required']) !!}
            </div>
            <div class="form-group" id="tramites">
                <h3>Trámites a realizar</h3>
                            
            </div>                    
        </div>             
    </div>    
</form>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/nuevaSolicitud.js')}}"></script>