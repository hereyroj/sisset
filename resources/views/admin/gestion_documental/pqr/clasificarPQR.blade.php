{!! Form::open(['id'=>'frm-clasificar-pqr']) !!}
<input type="hidden" name="pqr_id" value="{{$id}}" id="pqr_id">
<div class="form-group">
    <label class="label-form" for="series">Serie</label>
    {!! Form::select('series', $series, null, ['id'=>'series', 'class'=>'form-control']) !!}
</div>
<div class="form-group">
    <label class="label-form" for="subseries">Sub serie</label>
    <select name="subseries" id="subseries" class="form-control"></select>
</div>
<div class="form-group">
    <label class="label-form" for="tiposdocumentos">Tipo documento</label>
    <select name="tiposdocumentos" id="tiposdocumentos" class="form-control"></select>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/clasificarPQR.js')}}"></script>