$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
    container: '.jconfirm-light'
});

var fecha1 = $('#fecha_mandamiento').val();
var $inputFecha1 = $('#fecha_mandamiento').pickadate();
var pickerFecha1 = $inputFecha1.pickadate('picker');
pickerFecha1.set('select', fecha1, {
    format: 'yyyy-mm-dd'
});

$("#documento_mandamiento").change(function () {
    pdffile = document.getElementById("documento_mandamiento").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewerMandamiento').attr('src', pdffile_url);
});