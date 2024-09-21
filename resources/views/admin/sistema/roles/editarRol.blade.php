<form>
    <input type="hidden" name="id" value="{{$rol->id}}">
    <div class="form-group">
        <label for="name" class="control-label">Nombre</label>
        <input id="name" type="text" class="form-control" name="name" value="{{ $rol->name }}">
    </div>
    <div class="form-group">
        <label for="permisos" class="control-label">Permisos</label>
        <hr>
        @foreach($permisos as $permiso)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permisos[]" id="permisos[]" value="{{$permiso->id}}" @if($rol->existPermission($permiso->name)) checked @endif> {{$permiso->name}}
                </label>
            </div>
        @endforeach
    </div>
</form>