$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
    container: '.jconfirm-light'
});

$("#documento_mandamiento").change(function () {
    pdffile = document.getElementById("documento_mandamiento").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewerMandamiento').attr('src', pdffile_url);
});