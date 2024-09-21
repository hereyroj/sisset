$("input:file").change(function () {
    pdffile = document.getElementById("consignacion").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});

var fecha = $('#fecha_pago').val();
var $input = $('#fecha_pago').pickadate();
var picker = $input.pickadate('picker');
picker.set('select', fecha, {
    format: 'yyyy-mm-dd'
});