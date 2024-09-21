<h4>Asunto</h4>
<p>{{$pqr->asunto}}</p>
<h4>Descripción</h4>
<p>{{$pqr->descripcion}}</p>
<h4>Documento radicado</h4>
@if($pqr->documento_radicado != null)
    <a href="{{url('admin/mis-pqr/documentoRadicado/'.$pqr->uuid)}}" class="btn btn-secondary">Ver</a>
@else
    @if(auth()->user()->hasAnyRoles(['Administrador','Administrador PQR']))
        <button type="button" class="btn btn-secondary" onclick="reUploadFileRadicado({{$pqr->id}})">Subir</button>
    @else
        Sin información. Por favor contacta al encargado de PQR.
    @endif
@endif
<h4>Anexos</h4>
@if($pqr->anexos != null)
    <a href="{{url('admin/mis-pqr/anexos/'.$pqr->uuid)}}" class="btn btn-secondary">Ver</a>
@else
    Sin anexos
@endif
<h4>PDF radicación</h4>
@if($pqr->pdf != null)
    <a href="{{url('admin/mis-pqr/pdf/'.$pqr->uuid)}}" class="btn btn-secondary">Ver</a>
@elseif($pqr->getMedioTraslado->name == 'FORMULARIO WEB' && auth()->user()->hasAnyRoles(['Administrador','Administrador PQR']))
    <button type="button" class="btn btn-secondary" onclick="reGenerarPDF({{$pqr->id}})">Generar</button>
@else
    Sin PDF
@endif