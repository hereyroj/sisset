function obtenerRecibosServicioModal(id) {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerRecibosServicio/' + id,
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#recibosServicio').empty().html(response);
    })
}

function subirRecibos(id) {
    $.confirm({
        title: 'Subir recibos',
        content: 'url:/admin/tramites/solicitudes/subirRecibos/' + id,
        buttons: {
            subir: {
                text: 'Subir',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/subirRecibos',
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
                            self.buttons.subir.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerRecibosServicioModal(id);
                        }).fail(function () {
                            self.buttons.subir.disable();
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