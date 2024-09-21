{!! Form::open(['id' => 'frm-editar-tipo-documento']) !!}
<input type="hidden" name="tipo_id" value="{{$tipoDocumento->id}}">
<input type="hidden" name="subserie_id" value="{{$tipoDocumento->trd_documento_subserie_id}}">
<div class="form-group">
    <label for="serie" class="label-form">Sub-serie</label>
    {!! Form::select('serie', $series, $tipoDocumento->hasSubSerie->trd_documento_serie_id, ['class'=>'form-control', 'id'=>'serie']) !!}
</div>
<div class="form-group">
    <label for="subserie" class="label-form">Sub-serie</label>
    <select class="form-control" name="subserie" id="subserie"></select>
</div>
<div class="form-group">
    <label class="label-form" for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control" value="{{$tipoDocumento->name}}">
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/trd/editarTipoDocumento.js')}}"></script>