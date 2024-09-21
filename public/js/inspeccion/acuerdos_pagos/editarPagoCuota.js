$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fecha_pago').val();
var $input = $('#fecha_pago').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});