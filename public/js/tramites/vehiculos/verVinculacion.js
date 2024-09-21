$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fechaVinculacion').val();
var $input = $('#fechaVinculacion').pickadate();
var picker = $input.pickadate('picker');

picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});