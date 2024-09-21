<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 logo">
            <img src="{{asset('img/logo_pos.png')}}" width="auto" height="auto">
        </div>
        <div class="col-md-4">
            <div class="row" style="margin:0;padding:0;">
                {{ \anlutro\LaravelSettings\Facade::get('empresa-nombre') }}
            </div>
            <div class="row" style="margin:0;padding:0;">
                Turno: {{$solicitud->hasTurno()->turno}}<br>
                Placa: {{$solicitud->placa}}<br>
                @if($solicitud->hasUsuarioSolicitante->tipo_usuario == 'G')
                    CC: {{$solicitud->hasUsuarioSolicitante->numero_documento}}<br>
                @else
                    CC: N/A<br>
                @endif
                Hora: {{$turno->created_at}}
            </div>
        </div>
    </div>
</div>