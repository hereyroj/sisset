function obtenerFinalizacionesServicioModal(id) {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerFinalizacionServicio/' + id,
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#finalizacionesServicio').empty().html(response);
    })
}

function agregarFinalizacionServicio(id) {
    $.confirm({
        title: 'Agregar finalización del servicio',
        content: 'url:/admin/tramites/solicitudes/finalizarTramiteF1/' + id,
        buttons: {
            agregar: {
                text: 'Agregar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/finalizarTramiteF2',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.agregar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerFinalizacionesServicioModal(id);
                        }).fail(function () {
                            self.buttons.agregar.disable();
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

function anularSustrato(finalizacionId, sustratoId) {
    $.confirm({
        title: 'Anular sustrato',
        content: 'url:/admin/tramites/solicitudes/anularSustrato/' + finalizacionId + '/' + sustratoId,
        buttons: {
            anular: {
                text: 'Anular',
                btnClass: 'btn-red',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/anularSustrato',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.anular.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerFinalizacionesServicioModal("{{$id}}");
                        }).fail(function () {
                            self.buttons.anular.disable();
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