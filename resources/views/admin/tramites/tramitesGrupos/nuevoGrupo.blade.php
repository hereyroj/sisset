@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form>
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" name="nombre" class="form-control" required value="{{old('nombre')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="codigo">Código</label>
        <input type="text" name="codigo" class="form-control" required value="{{old('codigo')}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="tramites">Tramites</label>
        <hr/>
        @foreach($tramites as $tramite)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="tramites[]" id="tramites[]" value="{{$tramite->id}}"> {{$tramite->name}}
                </label>
            </div>
        @endforeach
    </div>
</form>