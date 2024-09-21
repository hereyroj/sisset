<form enctype="multipart/form-data">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="logo" name="logo">
        <label class="custom-file-label" for="logo">Logo</label>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Nombre</label>
        <input type="text" class="form-control" name="nombre" required>
    </div>
    <div class="form-group">
        <label class="control-label" for="">Contraseña (opcional)</label>
        <input type="password" class="form-control" name="password1">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Repetir contraseña</label>
        <input type="password" class="form-control" name="password2">
    </div>
    <div class="form-group">
        <label class="control-label" for="">Descripción</label>
        <textarea class="form-control" name="descripcion" required></textarea>
    </div>
</form>