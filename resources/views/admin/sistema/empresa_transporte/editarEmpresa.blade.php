<form>
    <input type="hidden" name="id" value="{{$empresa->id}}">
    <div class="form-group">
        <label class="control-label" for="nit">Nit</label>
        <input type="text" name="nit" class="form-control" required value="{{$empresa->nit}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="nombre">Nombre</label>
        <input type="text" name="name" class="form-control" required value="{{$empresa->name}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="correo">Correo electrónico</label>
        <input type="email" name="email" class="form-control" value="{{$empresa->email}}">
    </div>
    <div class="form-group">
        <label class="control-label" for="telefono">Teléfono</label>
        <input type="text" name="telephone" class="form-control" value="{{$empresa->telephone}}">
    </div>
</form>