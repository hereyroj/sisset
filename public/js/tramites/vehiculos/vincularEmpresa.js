$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

$('#desvincularActual').on('change', function () {
    if (this.checked) {
        $('#fechaDesvinculacion').prop("disabled", false);
    } else {
        $('#fechaDesvinculacion').prop("disabled", true);
    }
});