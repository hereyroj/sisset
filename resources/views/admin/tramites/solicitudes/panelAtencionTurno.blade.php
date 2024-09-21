<style>
    table {
        width: 100%;
    }

    table th, table td {
        padding: 5px;
    }
</style>
<form>
    <input type="hidden" name="turno" id="turno" value="{{$turno->id}}">
    <input type="hidden" name="tramite_solicitud" id="tramite_solicitud" value="{{$turno->tramite_solicitud_id}}">
    <div class="row">
        <div class="col-md-3" id="tramite_solicitud_info" style="border-right:2px solid #ccc;">
            <h3>Información del turno</h3>
            <div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Turno</label>
                <p class="form-control-static">{{$turno->turno}}</p>
            </div>
            <div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Tipo Documento Identidad</label>
                <p class="form-control-static">{{$turno->hasUsuarioSolicitante->hasTipoDocumentoIdentidad->name}}</p>
            </div>
            <div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label" for="numero_documento">Número Documento Identidad</label>
                <p class="form-control-static">{{$turno->hasUsuarioSolicitante->numero_documento}}</p>
            </div>
            <div class="form-group general" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label" for="nombre_usuario">Nombre usuario</label>
                <p class="form-control-static">{{$turno->hasUsuarioSolicitante->nombre_usuario}}</p>
            </div>
            <div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Origen de la solicitud</label>
                <p class="form-control-static">{{$turno->hasOrigen->name}}</p>
            </div>
            <div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Preferente</label>
                <p class="form-control-static">
                    @if($turno->preferente == 0)
                        NO
                    @elseif($turno->preferente == 1)
                        SI
                    @else
                        NO
                    @endif
                </p>
            </div>
            <h3>Información del tramite</h3>
            <div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Servicios</label>
                <p class="form-control-static">{{$turno->hasSolicitud->servicios}}</p>
            </div>
            <div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Tramite Grupo</label>
                <p class="form-control-static">{{$turno->hasSolicitud->hasTramiteGrupo->name}}</p>
            </div>
            <div class="form-group" style="border-bottom: 1px solid #8eb4cb;">
                <label class="control-label">Tramites</label>
                @foreach ($turno->hasSolicitud->hasTramites as $tramite)                
                <p class="form-control-static">{{$tramite->name}}</p>     
                @endforeach          
            </div>
        </div>
        <div class="col-md-9" id="tramite_solicitud_estados">
            <div class="row">
                <div class="col-md-12" style="border-bottom:2px solid #ccc; padding-bottom: 20px;" id="documentosRadicados">
                    <h3>Documentos radicados
                        <button type="button" class="btn btn-primary" onclick="actualizarDocumentosRadicados({{$turno->tramite_servicio_id}});">
                            <i class="fas fa-sync"></i> Actualizar
                        </button>
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Vigencia</th>
                                <th>Consecutivo</th>
                                <th>Archivos</th>
                                <th>Radicado por</th>
                                <th>Fecha radicación</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($turno->hasSolicitud->hasRadicados() as $radicado)
                                <tr>
                                    <td>{{$radicado->vigencia}}</td>
                                    <td>{{$radicado->consecutivo}}</td>
                                    <td>
                                        <button type="button" class="btn btn-secondary" onclick="obtenerArchivos({{$radicado->id}})">Obtener</button>
                                    </td>
                                    <td>{{$radicado->hasFuncionario->name}}</td>
                                    <td>{{$radicado->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($turno->hasSolicitud->hasTramiteGrupo->name != 'LICENCIAS')
            <div class="row">
                <div class="col-md-12" style="border-bottom:2px solid #ccc; padding-bottom: 20px;">
                    <h3>Servicios de la solicitud
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" onclick="obtenerServiciosSolicitud();">
                                <i class="fas fa-sync"></i> Actualizar
                            </button>
                            <button type="button" onclick="agregarServicioSolicitud();" class="btn btn-info">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Añadir
                            </button>
                        </div>
                    </h3>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Vehículo Servicio</th>
                            <th>Vehículo Clase</th>
                            <th>Fecha y hora</th>
                            <th>Tramites</th>
                            <th>Estados</th>
                            <th>Carpetas</th>
                            <th>Recibos</th>
                            <th>Finalización</th>
                        </tr>
                        </thead>
                        <tbody id="serviciosSolicitud">
                        @foreach($turno->hasSolicitud->hasServicios as $servicio)
                            <tr>
                                <td>{{$servicio->placa}}</td>
                                <td>{{$servicio->hasVehiculoServicio->name}}</td>
                                <td>{{$servicio->hasVehiculoClase->name}}</td>
                                <td>{{$servicio->created_at}}</td>
                                <td>
                                    @foreach($servicio->hasTramites as $tramite)
                                        <span class="badge badge-pill badge-primary">{{$tramite->name}}</span>
                                    @endforeach
                                </td>
                                <td><button type="button" class="btn btn-secondary" onclick="obtenerEstadosServicio({{$servicio->id}})">Ver</button></td>
                                <td><button type="button" class="btn btn-secondary" onclick="obtenerCarpetasServicio({{$servicio->id}})">Ver</button></td>
                                <td><button type="button" class="btn btn-secondary" onclick="obtenerRecibosServicio({{$servicio->id}})">Ver</button></td>
                                <td><button type="button" class="btn btn-secondary" onclick="obtenerFinalizacionServicio({{$servicio->id}})">Ver</button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-12" style="border-bottom:2px solid #ccc; padding-bottom: 20px;">
                    <h3>Licencias de la solicitud
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" onclick="obtenerLicenciasSolicitud();">
                                <i class="fas fa-sync"></i> Actualizar
                            </button>
                            <button type="button" onclick="nuevaLicenciaSolicitud();" class="btn btn-info">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva
                            </button>
                        </div>
                    </h3>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Sustrato</th>
                            <th>Categorías</th>
                            <th>Registrada por</th>
                            <th>Registrada el</th>
                            <th>Tipo documento conductor</th>
                            <th>Número documento conductor</th>
                            <th>Nombre conductor</th>
                        </tr>
                        </thead>
                        <tbody id="licenciasSolicitud">
                        @foreach($turno->hasSolicitud->hasLicencias as $licencia)
                            <tr>
                                <td>{{$licencia->hasSustrato->numero}}</td>
                                <td>
                                    @foreach ($licencia->hasCategorias as $categoria)
                                    <span class="badge badge-pill badge-primary">{{$categoria->name}}</span>
                                    @endforeach
                                </td>
                                <td>{{$licencia->hasFuncionario->name}}</td>
                                <td>{{$licencia->created_at}}</td>
                                <td>{{$licencia->hasTurno->hasUsuarioSolicitante->hasTipoDocumentoIdentidad->name}}</td>
                                <td>{{$licencia->hasTurno->hasUsuarioSolicitante->numero_documento}}</td>
                                <td>{{$licencia->hasTurno->hasUsuarioSolicitante->nombre_usuario}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/tramites/solicitudes/panelAtencionTurno.js')}}"></script>
<script type="text/ecmascript" src="{{asset('js/tramites/solicitudes/es_panelAtencionTurno.js')}}"></script>