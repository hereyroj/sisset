$("#encabezado_documento").change(function () {
    encabezado_documento_pdffile = document.getElementById("encabezado_documento").files[0];
    encabezado_documento_pdffile_url = URL.createObjectURL(encabezado_documento_pdffile);
    $('#viewer_encabezado_documento').attr('src', encabezado_documento_pdffile_url);
});

$("#pie_documento").change(function () {
    pie_documento_pdffile = document.getElementById("pie_documento").files[0];
    pie_documento_pdffile_url = URL.createObjectURL(pie_documento_pdffile);
    $('#viewer_pie_documento').attr('src', pie_documento_pdffile_url);
});