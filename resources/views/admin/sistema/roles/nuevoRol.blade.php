<form>
    <div class="form-group">
        <label for="name" class="control-label">Nombre</label>
        <input id="name" type="text" class="form-control" name="name" required>
    </div>
    <div class="form-group">
        <label class="control-label">Permisos</label>
        <hr/>
        @foreach($permisos as $permiso)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permisos[]" id="permisos[]" value="{{$permiso->id}}"> {{$permiso->name}}
                </label>
            </div>
        @endforeach
    </div>
</form>