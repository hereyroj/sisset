window.Parsley.addValidator('maxFileSize', {
    validateString: function (_value, maxSize, parsleyInstance) {
        if (!window.FormData) {
            alert('You are making all developpers in the world cringe. Upgrade your browser!');
            return true;
        }
        var files = parsleyInstance.$element[0].files;
        return files.length != 1 || files[0].size <= maxSize * 1024;
    },
    requirementType: 'integer',
    messages: {
        en: 'El archivo no debe pesar mas de %s KB',
        es: 'El archivo no debe pesar mas de %s KB'
    }
}).addValidator('fileextension', function (value, requirement) {
    var fileExtension = value.split('.').pop();
    return fileExtension === requirement;
}, 32).addMessage('es', 'fileextension', 'El tipo de archivo no es admitido. Solo de admite PDF.');

$("input:file").change(function () {
    pdffile = document.getElementById("archivo").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});