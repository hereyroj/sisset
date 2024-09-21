function obtenerEstadosServicioModal(id) {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerEstadosServicio/' + id,
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#estadosServicio').empty().html(response);
    })
}

function asignarEstadoServicio(id) {
    $.confirm({
        title: 'Asignar estado',
        content: 'url:/admin/tramites/solicitudes/asignarEstadoServicio/' + id,
        buttons: {
            asignar: {
                text: 'Asignar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/asignarEstadoServicio',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.asignar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerEstadosServicioModal(id);
                        }).fail(function () {
                            self.buttons.asignar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la validación de la solicitud.',
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
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function selectAll(e) {
    if (e.checked) {
        $('#estadosServicio table').find('input:checkbox').prop('checked', true);
    } else {
        $('#estadosServicio table').find('input:checkbox').prop('checked', false);
    }
}

function generarDevolucion(id) {
    var servicio = id;
    var estados = $("input[name='estados\\[\\]']").map(function () {
        if ($(this).is(':checked')) {
            return $(this).val();
        }
    }).get();

    if (estados.length <= 0 || estados == undefined) {
        $.alert({
            icon: 'glyphicon glyphicon-warning-sign',
            title: 'Sin selección!',
            columnClass: 'medium',
            content: 'No se han seleccionado elementos.',
            buttons: {
                aceptar: {
                    text: 'Aceptar',
                    btnClass: 'btn-primary',
                    action: function () {

                    }
                }
            }
        });
    } else {
        var request = new XMLHttpRequest();
        request.open('POST', '/admin/tramites/solicitudes/generarDevolucion', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        request.responseType = 'blob';

        request.onload = function () {
            // Only handle status code 200
            if (request.status === 200) {
                // Try to find out the filename from the content disposition `filename` value
                var disposition = request.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'file.pdf');

                // The actual download
                var blob = new Blob([request.response], {
                    type: 'application/pdf'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();

                document.body.removeChild(link);
            }

            // some error handling should be done here...
        };

        request.send('data=' + JSON.stringify({
            "servicio": servicio,
            "estados": estados
        }));
    }
}