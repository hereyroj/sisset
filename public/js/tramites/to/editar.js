$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fechaVencimiento').val();
var $input = $('#fechaVencimiento').pickadate();
var picker = $input.pickadate('picker');

picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});