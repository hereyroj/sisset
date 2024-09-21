$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

$("input:file").change(function () {
    pdffile = document.getElementById("documento").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});

var fecha = $('#fecha_finalizacion').val();
var $inputFecha = $('#fecha_finalizacion').pickadate();
var pickerFecha = $inputFecha.pickadate('picker');
pickerFecha.set('select', fecha, {
    format: 'yyyy-mm-dd'
});