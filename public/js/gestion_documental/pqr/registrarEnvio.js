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
});