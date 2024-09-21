function addRadicado() {
    var num_radicado = $('#radicados').find('input');
    var nombre = 'radicados_respuesta_' + (num_radicado.length + 1);
    $('#radicados').append('<div class="input-group"><input type="text" name="radicados_respuesta[]" id="' + nombre + '" class="form-control" placeholder="{{\anlutro\LaravelSettings\Facade::get(\'empresa-sigla\')}}-AÑO-100-NUMERO"><span class="input-group-addon boton-eliminar" onclick="delRadicado(' + nombre + ');">X</span></div>');
}

function delRadicado(radicado) {
    $('#' + radicado).parent('div').remove();
}