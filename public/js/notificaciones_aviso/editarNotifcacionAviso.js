$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

function cambiarDocumento() {
    $('.actual_documento').hide();
    $('.nuevo_documento').removeClass('hide');
}

var fecha = $('#fecha_publicacion').val();
var $input = $('#fecha_publicacion').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});

var fecha2 = $('#fecha_desfijacion').val();
var $input2 = $('#fecha_desfijacion').pickadate();
var picker2 = $input2.pickadate('picker');
picker2.set('select', fecha2, {
    format: 'yyyy-mm-dd'
});