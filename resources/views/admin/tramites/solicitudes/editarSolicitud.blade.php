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
    <input type="hidden" name="id" value="{{$solicitud->id}}">
    <div class="row">
        <div class="col-lg-12">
            <h3>Información de la solicitud</h3>
            <div class="form-group">
                <label class="control-label" for="servicios">Cantidad de servicios</label>
                <input type="number" name="servicios" required min="1" class="form-control" value="{{$solicitud->servicios}}">
            </div>
            <div class="form-group">
                <fieldset disabled>
                <label class="control-label" for="grupo">Grupo de tramites</label>
                {!! Form::select('grupo', $tramitesGrupos, $solicitud->tramite_grupo_id, ['id'=>'grupo', 'class'=>'form-control', 'readonly', 'disable']) !!}
                </fieldset>
            </div>
            <div class="form-group" id="tramites">
                <h3>Trámites a realizar</h3>

            </div>
            <div class="form-group">
                <label class="control-label" for="observacion">Observación</label>
                <textarea name="observacion" class="form-control" required rows="7">{{$solicitud->observacion}}</textarea>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/editarSolicitud.js')}}"></script>