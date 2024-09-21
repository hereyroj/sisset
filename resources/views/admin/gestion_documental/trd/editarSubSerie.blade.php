{!! Form::open(['id' => 'frm-editar-sub-serie']) !!}
<input type="hidden" name="subserie_id" value="{{$subSerie->id}}">
<div class="form-group">
    <label for="serie" class="label-form">Serie</label>
    {!! Form::select('serie', $series, $subSerie->trd_documento_serie_id, ['class'=>'form-control', 'id'=>'serie']) !!}
</div>
<div class="form-group">
    <label class="label-form" for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control" value="{{$subSerie->name}}">
</div>
<div class="form-group">
    <div class="panel panel-default">
       <div class="card-body">
            <h4>Retención:</h4>
            <div class="form-group">
                <input type="checkbox" name="gestion" id="gestion" value="X" @if($subSerie->archivo_gestion == 'X') checked @endif>
                <label for="gestion" class="label-form">Archivo de gestión</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="central" id="central" value="X" @if($subSerie->archivo_central == 'X') checked @endif>
                <label for="central" class="label-form">Archivo central</label>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="panel panel-default">
       <div class="card-body">
            <h4>Disposición final:</h4>
            <div class="form-group">
                <input type="checkbox" name="conservacion" id="conservacion" value="X" @if($subSerie->conservacion_total == 'X') checked @endif>
                <label for="conservacion" class="label-form">Conservación total</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="eliminacion" id="eliminacion" value="X" @if($subSerie->eliminacion == 'X') checked @endif>
                <label for="eliminacion" class="label-form">Eliminación</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="digitalizar" id="digitalizar" value="X" @if($subSerie->digitalizar == 'X') checked @endif>
                <label for="digitalizar" class="label-form">Digitalizar</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="seleccion" id="seleccion" value="X" @if($subSerie->seleccion == 'X') checked @endif>
                <label for="seleccion" class="label-form">Selección</label>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="descripcion" class="label-form">Descripción:</label>
    <textarea name="descripcion" id="descripcion" class="form-control">{{$subSerie->descripcion}}</textarea>
</div>
{!! Form::close() !!}