{!! Form::open(['id' => 'frm-crear-sub-serie']) !!}
<div class="form-group">
    <label for="serie" class="label-form">Serie</label>
    {!! Form::select('serie', $series, null, ['class'=>'form-control', 'id'=>'serie']) !!}
</div>
<div class="form-group">
    <label class="label-form" for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control">
</div>
<div class="form-group">
    <div class="panel panel-default">
       <div class="card-body">
            <h4>Retención:</h4>
            <div class="form-group">
                <input type="checkbox" name="gestion" id="gestion" value="X">
                <label for="gestion" class="label-form">Archivo de gestión</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="central" id="central" value="X">
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
                <input type="checkbox" name="conservacion" id="conservacion" value="X">
                <label for="conservacion" class="label-form">Conservación total</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="eliminacion" id="eliminacion" value="X">
                <label for="eliminacion" class="label-form">Eliminación</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="digitalizar" id="digitalizar" value="X">
                <label for="digitalizar" class="label-form">Digitalizar</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="seleccion" id="seleccion" value="X">
                <label for="seleccion" class="label-form">Selección</label>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="descripcion" class="label-form">Descripción:</label>
    <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
</div>
{!! Form::close() !!}