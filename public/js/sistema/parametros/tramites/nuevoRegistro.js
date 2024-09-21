$(document).ready(function () {

    $('.timepicker').pickatime({
        format: 'HH:i',
        formatSubmit: 'HH:i',
        hiddenSuffix: '_submit',
        container: '.jconfirm-light'
    });
    
});