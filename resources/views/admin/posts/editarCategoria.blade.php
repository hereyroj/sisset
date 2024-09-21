<form enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$categoria->id}}">
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text"class="form-control" name="nombre" id="nombre" required value="{{$categoria->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="slug">Slug</label>
        <input type="text"class="form-control" name="slug" id="slug" value="{{$categoria->slug}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="descripcion">Descripción</label>
        <textarea class="form-control" name="descripcion" id="descripcion" value="{{$categoria->description}}"></textarea>
    </div>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="icono" id="icono">
        <label class="custom-file-label" for="icono" data-browse="Elegir">Icono</label>
        @if($categoria->icon != null)<img src="{{asset('storage/posts/'.$categoria->icono)}}" class="img-thumbnail">@endif
    </div>
    <div class="form-group">
        <label class="control-label" for="categoria_superior">Categoría superior</label> 
        {{Form::select('categoria_superior', $categorias, $categoria->parent_category_id, ['class'=>'form-control', 'id'=>'categoria_superior'])}}
    </div>
</form>