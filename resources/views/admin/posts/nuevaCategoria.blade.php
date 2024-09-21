<form enctype="multipart/form-data">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="slug">Slug</label>
        <input type="text"class="form-control" name="slug" id="slug">
    </div>
    <div class="form-group">
        <label class="control-label" for="descripcion">Descripción</label>
        <textarea class="form-control" name="descripcion" id="descripcion"></textarea>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="icono" id="icono">
        <label class="custom-file-label" for="icono">Icono</label>
    </div>
    <div class="form-group">
        <label class="control-label" for="categoria_superior">Categoría superior</label>
        {{Form::select('categoria_superior', $categorias, null, ['class'=>'form-control', 'id'=>'categoria_superior'])}}
    </div>
</form>