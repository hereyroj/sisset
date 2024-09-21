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

$(document).ready(function () {
    $('.datepicker').each(function (index, elemento) {
        if ($(this).val() !== '') {
            var $input = $(this).pickadate();
            //alert($input);
            // Use the picker object directly.
            var picker = $input.pickadate('picker');
            picker.set('select', $(this).val(), {
                format: 'yyyy-mm-dd'
            });

        }
        picker = '';
    });
    $('.timepicker').each(function (index, elemento) {
        if ($(this).val() !== '') {
            var $input = $(this).pickatime();
            //alert($input);
            // Use the picker object directly.
            var picker = $input.pickatime('picker');
            picker.set('select', $(this).val(), {
                format: 'H:i'
            });

        }
        picker = '';
    });
});