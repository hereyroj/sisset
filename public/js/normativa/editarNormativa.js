$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    container: '.jconfirm-light'
});

var fecha = $('#fecha_expedicion').val();
var $input = $('#fecha_expedicion').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});

$("input:file").change(function () {
    pdffile = document.getElementById("documento").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});