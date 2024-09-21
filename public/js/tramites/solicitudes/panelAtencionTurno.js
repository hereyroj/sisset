function obtenerLicenciasSolicitud() {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerListadoLicencias/' + $('#tramite_solicitud').val(),
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#licenciasSolicitud').empty().html(response);
    })
}

function nuevaLicenciaSolicitud() {
    $.confirm({
        title: 'Registrar licencia',
        content: 'url:/admin/tramites/solicitudes/nuevaLicencia/' + $('#tramite_solicitud').val(),
        buttons: {
            asignar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/registrarLicencia',
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
                            obtenerLicenciasSolicitud();
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
                action: function () {

                }
            }
        }
    });
}

function obtenerCarpetasServicio(servicio_id) {
    $.confirm({
        title: 'Solicitudes de Carpetas',
        content: 'url:/admin/tramites/solicitudes/obtenerCarpetasServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function obtenerEstadosServicio(servicio_id) {
    $.confirm({
        title: 'Estados del Servicio',
        content: 'url:/admin/tramites/solicitudes/obtenerEstadosServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function obtenerFinalizacionServicio(servicio_id) {
    $.confirm({
        title: 'Finalización del Servicio',
        content: 'url:/admin/tramites/solicitudes/obtenerFinalizacionServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function obtenerRecibosServicio(servicio_id) {
    $.confirm({
        title: 'Recibos del Servicio',
        content: 'url:/admin/tramites/solicitudes/obtenerRecibosServicio/' + servicio_id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {

                }
            }
        }
    });
}

function obtenerServiciosSolicitud() {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerServiciosSolicitud/' + $('#tramite_solicitud').val(),
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        llamadoTurno.$content.find('#serviciosSolicitud').empty().html(response);
    }).fail(function () {
        $.alert({
            title: 'Error de conexión',
            content: 'No se ha podido conectar con el servidor.'
        });
    });
}

function agregarServicioSolicitud() {
    $.confirm({
        title: 'Agregar Servicio',
        content: 'url:/admin/tramites/solicitudes/agregarServicioSolicitud/' + $('#tramite_solicitud').val(),
        buttons: {
            agregar: {
                text: 'Agregar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/agregarServicioSolicitud',
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
                            obtenerServiciosSolicitud();
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
                action: function () {

                }
            }
        }
    });
}