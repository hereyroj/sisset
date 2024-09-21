$(document).ready(function () {

    $('.timepicker').pickatime({
        format: 'HH:i',
        formatSubmit: 'HH:i',
        hiddenSuffix: '_submit',
        container: '.jconfirm-light'
    });

    var hora_inicio = $('#inicio_atencion').val();
    var $input_hora_inicio = $('#inicio_atencion').pickatime();
    var picker_hora_inicio = $input_hora_inicio.pickatime('picker');
    
    picker_hora_inicio.set('select', hora_inicio, {
        format: 'H:i'
    });

    var hora_fin = $('#fin_atencion').val();
    var $input_hora_fin = $('#fin_atencion').pickatime();
    var picker_hora_fin = $input_hora_fin.pickatime('picker');

    picker_hora_fin.set('select', hora_fin, {
        format: 'H:i'
    });

});