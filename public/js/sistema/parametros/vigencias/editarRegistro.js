$(document).ready(function () {
    
    $('.datepicker').pickadate({
        selectYears: true,
        selectMonths: true,
        formatSubmit: 'yyyy-mm-dd',
        container: '.jconfirm-light'
    });

    var fecha_inicial = $('#inicio_vigencia').val();
    var $input_inicial = $('#inicio_vigencia').pickadate();
    var picker_inicial = $input_inicial.pickadate('picker');

    picker_inicial.set('select', fecha_inicial, {
        format: 'yyyy-mm-dd'
    });

    var fecha_final = $('#fin_vigencia').val();
    var $input_final = $('#fin_vigencia').pickadate();
    var picker_final = $input_final.pickadate('picker');

    picker_final.set('select', fecha_final, {
        format: 'yyyy-mm-dd'
    });

});