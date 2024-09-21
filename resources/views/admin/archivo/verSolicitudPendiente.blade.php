<h3>Información del tramite</h3>
<div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label">Tipo Documento Identidad</label>
    <p class="form-control-static">{{$solicitud->hasTurnoActivo()->hasUsuarioSolicitante->hasTipoDocumentoIdentidad->name}}</p>
</div>
<div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label" for="numero_documento">Número Documento Identidad</label>
    <p class="form-control-static">{{$solicitud->hasTurnoActivo()->hasUsuarioSolicitante->numero_documento}}</p>
</div>
<div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label" for="nombre_usuario">Nombre usuario</label>
    <p class="form-control-static">{{$solicitud->hasTurnoActivo()->hasUsuarioSolicitante->nombre_usuario}}</p>
</div>
<div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label">Placa</label>
    <p class="form-control-static">{{$solicitud->placa}}</p>
</div>
<div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label">Tramite</label>
    <p class="form-control-static">{{$solicitud->hasTramite->name}}</p>
</div>
<div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label">Clase de vehículo</label>
    <p class="form-control-static">{{$solicitud->hasVehiculoClase->name}}</p>
</div>
<div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label">Servicio del vehículo</label>
    <p class="form-control-static">{{$solicitud->hasVehiculoServicio->name}}</p>
</div>
<div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
    <label class="control-label">Origen de la solicitud</label>
    <p class="form-control-static">{{$solicitud->hasTurnoActivo()->hasOrigen->name}}</p>
</div>