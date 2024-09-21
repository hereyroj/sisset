$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
    container: '.jconfirm-light'
});

function addProceso() {
    var num_proceso = $('#procesos').find('input');
    var nombre = 'proceso_' + (num_proceso.length + 1);
    $('#procesos').append('<div class="input-group  mb-3"><input type="text" name="procesos[]" id="' + nombre + '" class="form-control"><div class="input-group-append"><span class="input-group-text boton-eliminar" onclick="delProceso(\'' + nombre + '\');" title="Eliminar">X</span></div></div>');
}

function delProceso(proceso) {
    $('#' + proceso).parent('div').remove();
}

$("#cant_cuotas").change(function () {
    var num_cuota = $("#cuotas").find("div").length;
    if (num_cuota === 0) {
        num_cuota = 1;
    }
    var cant_cuotas = $("#cant_cuotas").val();
    if (num_cuota < cant_cuotas) {
        for (var i = num_cuota; i <= cant_cuotas; i++) {
            $("#cuotas").append('<div id="cuota' + i + '" class="row mb-3"><div class="form-group col-md-6"><label class="control-label">Valor cuota ' + i + '</label><input type="text" name="cuota' + i + '_valor" id="cuota' + i + '_valor" class="form-control"></div><div class="form-group col-md-6"><label class="control-label">Vencimiento cuota ' + i + '</label><input type="text" name="cuota' + i + '_fecha_vencimiento" id="cuota' + i + '_fecha_vencimiento" class="form-control datepicker"></div></div>');
        }
    } else {
        for (var i = num_cuota; i > cant_cuotas; i--) {
            $("#cuota" + i).remove();
        }
    }
    $('.datepicker').pickadate({
        selectYears: true,
        selectMonths: true,
        formatSubmit: 'yyyy-mm-dd',
        container: '.jconfirm-light'
    });
});

$("input:file").change(function () {
    pdffile = document.getElementById("acuerdo").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});