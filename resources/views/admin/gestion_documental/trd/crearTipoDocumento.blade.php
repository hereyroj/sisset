{!! Form::open(['id' => 'frm-crear-tipo-documento']) !!}
<div class="form-group">
    <label for="serie" class="label-form">Sub-serie</label>
    {!! Form::select('serie', $series, null, ['class'=>'form-control', 'id'=>'serie']) !!}
</div>
<div class="form-group">
    <label for="subserie" class="label-form">Sub-serie</label>
    <select class="form-control" name="subserie" id="subserie"></select>
</div>
<div class="form-group">
    <label class="label-form" for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control">
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/trd/crearTipoDocumental.js')}}"></script>