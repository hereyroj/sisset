$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

$("#documento").change(function () {
    pdffile = document.getElementById("documento").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});

$("#pantallazo_runt").change(function () {
    pdffile = document.getElementById("pantallazo_runt").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer2').attr('src', pdffile_url);
});

var fecha1 = $('#fecha_notificacion').val();
var $inputFecha1 = $('#fecha_notificacion').pickadate();
var pickerFecha1 = $inputFecha1.pickadate('picker');
pickerFecha1.set('select', fecha1, {
    format: 'yyyy-mm-dd'
});

var fecha2 = $('#fecha_max_presentacion').val();
var $inputFecha2 = $('#fecha_max_presentacion').pickadate();
var pickerFecha2 = $inputFecha2.pickadate('picker');
pickerFecha2.set('select', fecha2, {
    format: 'yyyy-mm-dd'
});