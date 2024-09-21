$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var vigente_desde = $('#vigente_desde').val();
var $input_vigente_desde = $('#vigente_desde').pickadate();
var picker_vigente_desde = $input_vigente_desde.pickadate('picker');

picker_vigente_desde.set('select', vigente_desde, {
    format: 'yyyy-mm-dd'
});

var vigente_hasta = $('#vigente_hasta').val();
var $input_vigente_hasta = $('#vigente_hasta').pickadate();
var picker_vigente_hasta = $input_vigente_hasta.pickadate('picker');

picker_vigente_hasta.set('select', vigente_hasta, {
    format: 'yyyy-mm-dd'
});