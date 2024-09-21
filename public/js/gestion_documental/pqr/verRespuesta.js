$(document).ready(function () {
    $('.datepicker').pickadate({
        selectYears: true,
        selectMonths: true,
        formatSubmit: 'yyyy-mm-dd',
        container: '.jconfirm-light'
    });

    $('.timepicker').pickatime({
        format: 'HH:i',
        formatSubmit: 'HH:i',
        hiddenSuffix: '_submit',
        container: '.jconfirm-light'
    });

    var fecha = $('#fecha_envio').val();
    var $input = fecha.pickadate();
    var picker = $input.pickadate('picker');
    picker.set('select', fecha, {
        format: 'yyyy-mm-dd'
    });

    var hora = $('#hora_envio').val();
    var $inputhora = $('#hora_envio').pickatime();
    var pickerhora = $inputhora.pickatime('picker');
    pickerhora.set('select', hora, {
        format: 'H:i'
    });
});