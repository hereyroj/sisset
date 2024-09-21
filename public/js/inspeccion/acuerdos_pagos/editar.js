$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fecha_acuerdo').val();
var $input = $('#fecha_acuerdo').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});

function addProceso() {
    var num_proceso = $('#procesos').find('input');
    var nombre = 'proceso_' + (num_proceso.length + 1);
    $('#procesos').append('<div class="input-group  mb-3"><input type="text" name="procesos[]" id="' + nombre + '" class="form-control"><div class="input-group-append"><span class="input-group-text boton-eliminar" onclick="delProceso(\'' + nombre + '\');" title="Eliminar">X</span></div></div>');
}

function delProceso(proceso) {
    $('#' + proceso).parent('div').remove();
}

$("input:file").change(function () {
    pdffile = document.getElementById("acuerdo").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});