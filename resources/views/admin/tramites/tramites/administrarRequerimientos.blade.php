<form id="frmCrearRequerimiento">
    <input type="hidden" name="id" id="tramite" value="{{$id}}">
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input class="form-control" type="text" name="nombre" required>
    </div>
    <div class="form-group">
        <label class="control-label">Descripción</label>
        <textarea class="form-control" name="descripcion" required></textarea>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary" onclick="crearRequerimiento()">Crear</button>
        <button type="reset" class="btn btn-danger">Borrar</button>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="listadoRequerimientos">
            @foreach ($requerimientos as $requerimiento)
            <tr>
                <td>{{$requerimiento->name}}</td>
                <td>{{$requerimiento->description}}</td>
                <td><button type="button" class="btn btn-secondary btn-block" title="Editar" onclick="editarRequerimiento({{$requerimiento->id}})">Editar</button>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script type="text/javascript" src="{{asset('js/tramites/tramites/administrarRequerimientos.js')}}"></script>