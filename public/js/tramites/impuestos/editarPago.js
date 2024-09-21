$("input:file").change(function () {
    pdffile = document.getElementById("archivo_consignacion").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    //$('#viewer').attr('src',pdffile_url);
    $('#frmEditarPago').find('.preview').remove();
    $('#frmEditarPago').append('<div class="preview">Previsualización<br><iframe id="viewer" frameborder="0" scrolling="no" width="100%" height="400" src="' + pdffile_url + '"></iframe></div>')
});