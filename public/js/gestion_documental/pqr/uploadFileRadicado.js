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
    },
    32).addMessage('es', 'fileextension', 'El tipo de archivo no es admitido. Solo de admite PDF.');

function imprimirRadicado(tipoRadicado, idRadicado) {
    window.open('/admin/pqr/obtenerRadicado/' + tipoRadicado + '/' + idRadicado, "_blank");
}

$("input:file").change(function () {
    pdffile = document.getElementById("file").files[0];
    pdffile_url = URL.createObjectURL(pdffile);
    $('#viewer').attr('src', pdffile_url);
});

function uploadFileRadicado() {
    var frm = crearPqr.$content.find('form');
    if (frm.parsley().validate()) {
        $.ajax({
            url: '/admin/pqr/uploadFileRadicado',
            dataType: 'html',
            method: 'post',
            data: new FormData(frm[0]),
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (response) {
            if (response.search('danger') > 0) {
                $.alert({
                    title: 'Error',
                    content: response,
                    buttons: {
                        cerrar: {
                            text: 'Cerrar',
                            action: function () {}
                        }
                    }
                });
                return false;
            } else {
                crearPqr.setContent(response);
                crearPqr.buttons.cerrar.enable();
                crearPqr.buttons.cerrar.show();
            }
        }).fail(function () {
            $.alert({
                title: 'Error con el servidor',
                content: 'No se ha podido realizar la acción.',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
            return false;
        });
        return false;
    } else {
        $.alert({
            title: 'Error',
            content: 'Error en la validación del formulario.',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    action: function () {}
                }
            }
        });
        return false;
    }
}