$('#fecha_devolucion').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fecha_devolucion').val();
var $inputFecha = $('#fecha_devolucion').pickadate();
var pickerFecha = $inputFecha.pickadate('picker');
pickerFecha.set('select', fecha, {
    format: 'yyyy-mm-dd'
});