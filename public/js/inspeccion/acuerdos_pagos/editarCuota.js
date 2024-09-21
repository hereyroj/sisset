$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fecha_vencimiento').val();
var $input = $('#fecha_vencimiento').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});