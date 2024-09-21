{!! Form::open(['id'=>'frm-clasificacion-pqr']) !!}
<input type="hidden" name="m_clasificacion_id" value="{{$clasificacion->id}}" id="m_clasificacion_id">
<input type="hidden" name="m_pqr_id" value="{{$clasificacion->gd_pqr_id}}" id="m_pqr_id">
<div class="form-group">
    <label class="label-form" for="series">Serie</label>
    {!! Form::select('m_series', $series, $clasificacion->getDocumentoTipo->hasSubSerie->hasSerie->id, ['id'=>'m_series', 'class'=>'form-control']) !!}
</div>
<div class="form-group">
    <label class="label-form" for="m_subseries">Sub serie</label>
    <select name="m_subseries" id="m_subseries" class="form-control"></select>
</div>
<div class="form-group">
    <label class="label-form" for="m_tiposdocumentos">Tipo documento</label>
    <select name="m_tiposdocumentos" id="m_tiposdocumentos" class="form-control"></select>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{asset('js/gestion_documental/pqr/clasificacionPQR.js')}}"></script>