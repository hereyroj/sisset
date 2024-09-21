<form enctype='multipart/form-data'>
    <div class="col-md-8">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="avatar" id="avatar">
            <label class="custom-file-label" for="avatar">Seleccionar Avatar</label>
        </div>
        <div class="form-group">
            <label for="name" class="control-label">Nombre</label>
            <input id="name" type="text" class="form-control" name="name" required autofocus>
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Correo</label>
            <input id="email" type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label for="dependencia" class="control-label">Dependencia</label>
            {!! Form::select('dependencia', $dependencias, null, ['class' => 'form-control', 'required'=>'required']) !!}
        </div>
    </div>
    <div class="col-md-4">
        <label for="roles" class="control-label">Roles</label>
        @foreach($roles as $rol)
            <div class="col-md-12">
                <input type="checkbox" name="roles[]" id="{{$rol->name}}" value="{{$rol->id}}">
                <label for="roles[]" class="control-label">{{$rol->name}}</label>
            </div>
        @endforeach
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Activo</th>
                <th>Permisos individuales</th>
                <th>Desactivado</th>
                <th>Temporal</th>
                <th>Fecha terminación</th>
                <th>Hora terminación</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permisos as $permiso)
                <tr>
                    <td>
                        <input type="checkbox" name="permisos[]" id="{{$permiso->id}}" value="{{$permiso->name}}"/>
                    </td>
                    <td>
                        {{$permiso->name}}
                    </td>
                    <td>
                        <input type="checkbox" name="desactivar[]" value="{{$permiso->name}}" id="{{$permiso->id}}"/>
                    </td>
                    <td>
                        <input type="checkbox" name="expira[]" value="{{$permiso->name}}" id="{{$permiso->id}}"/>
                    </td>
                    <td>
                        <input type="date" class="form-control datepicker" name="fecha{{$permiso->name}}" id="fecha{{$permiso->name}}"/>
                    </td>
                    <td>
                        <input type="date" class="form-control timepicker" name="hora{{$permiso->name}}" id="hora{{$permiso->name}}"/>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</form>
<script type="text/javascript" src="{{asset('js/sistema/usuarios/nuevoUsuario.js')}}"></script>