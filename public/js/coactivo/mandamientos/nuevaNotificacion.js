$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
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