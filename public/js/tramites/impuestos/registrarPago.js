$("input:file").change(function () {
    pdffile = document.getElementById("archivo_consignacion").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});