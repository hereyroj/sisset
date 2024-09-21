$(document).ready(function () {
    obtenerTramitesSolicitudes();
    obtenerOrigenes();
    obtenerEstados();
    obtenerMotivosDescanso();
});

function obtenerTramitesSolicitudes() {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerTramites',
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#tramites_solicitados').find('table').remove();
        $('#tramites_solicitados').find('.text-center').remove();
        $('#tramites_solicitados').append(response);
    }).fail(function () {
        $.alert({
            title: 'Error en la conexión',
            content: 'No se ha podido obtener los tramites del día debido a un problema de conexión con el servidor.'
        });
    });
}

$('#tramites_solicitados').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#tramites_solicitados').find('table').remove();
            $('#tramites_solicitados').append(data);
        }
    });
});

var ns = null;

function nuevaSolicitud() {
    ns = $.confirm({
        title: 'Crear Solicitud',
        content: 'url:/admin/tramites/solicitudes/nuevaSolicitud',
        columnClass: 'col-md-12',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/registrarSolicitud',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.setContent(response);
                            self.setTitle('Terminado');
                            $('div.jconfirm-scrollpane').scrollTop(0);
                            obtenerTramitesSolicitudes();
                        }).fail(function () {
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

function obtenerOrigenes() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/solicitudes/obtenerListadoOrigenes',
        dataType: 'html',
    }).done(function (data) {
        $('#origenes').empty().html(data);
    });
}

function nuevoOrigen() {
    $.confirm({
        title: 'Nuevo  Origen',
        content: 'url:/admin/tramites/solicitudes/nuevoOrigen',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/nuevoOrigen',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.crear.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerOrigenes();
                    }).fail(function () {
                        self.buttons.crear.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function editarOrigen(id) {
    $.confirm({
        title: 'Editar  Origen',
        content: 'url:/admin/tramites/solicitudes/editarOrigen/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/editarOrigen',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerOrigenes();
                    }).fail(function () {
                        self.buttons.guardar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function obtenerEstados() {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerListadoEstados',
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#estados').empty().html(response);
    }).fail(function () {
        $.alert({
            title: 'Error en la conexión',
            content: 'No se ha podido obtener los tramites del día debido a un problema de conexión con el servidor.'
        });
    });
}

function nuevoEstado() {
    $.confirm({
        title: 'Nuevo  Estado',
        content: 'url:/admin/tramites/solicitudes/nuevoEstado',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/nuevoEstado',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.crear.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerEstadoes();
                    }).fail(function () {
                        self.buttons.crear.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function editarEstado(id) {
    $.confirm({
        title: 'Editar  Estado',
        content: 'url:/admin/tramites/solicitudes/editarEstado/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/editarEstado',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerEstados();
                    }).fail(function () {
                        self.buttons.guardar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function reImprimirTurno(id) {
    $.confirm({
        title: 'Re-imprimir turno',
        content: 'url:/admin/tramites/solicitudes/reImprimirTurno/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function filtrarSolicitudes() {
    if ($('#filtrarSolicitudes').val() == undefined) {
        $.alert({
            title: 'Error!',
            content: 'No se ha especificado el valor de búsqueda.',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    action: function () {
                        $('#filtrarSolicitudes').focus();
                    }
                }
            }
        });
    }
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/solicitudes/filtrar/' + $('#filtrarSolicitudes').val() + '/' + $('#filtroSolicitudes').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#tramites_solicitados').find('table').remove();
                $('#tramites_solicitados').append(data);
            } else {
                $.alert({
                    title: 'Error!',
                    content: 'No se ha encontrado registros con la información suministrada.',
                    buttons: {
                        cerrar: {
                            text: 'Cerrar',
                            action: function () {}
                        }
                    }
                });
            }
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                content: '<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {}
                    }
                }
            });
        });
}

function obtenerMotivosDescanso() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/solicitudes/obtenerListadoMotivosDescanso',
        dataType: 'html',
    }).done(function (data) {
        $('#origenes').empty().html(data);
    });
}

function nuevoMotivoDescanso() {
    $.confirm({
        title: 'Nuevo  MotivoDescanso',
        content: 'url:/admin/tramites/solicitudes/nuevoMotivoDescanso',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/nuevoMotivoDescanso',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.crear.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerMotivosDescanso();
                    }).fail(function () {
                        self.buttons.crear.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function editarMotivoDescanso(id) {
    $.confirm({
        title: 'Editar  Motivo Descanso',
        content: 'url:/admin/tramites/solicitudes/editarMotivoDescanso/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/editarMotivoDescanso',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerMotivosDescanso();
                    }).fail(function () {
                        self.buttons.guardar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function editarSolicitante(id) {
    $.confirm({
        title: 'Editar  Solicitante',
        content: 'url:/admin/tramites/solicitudes/editarSolicitante/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/actualizarSolicitante',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                    }).fail(function () {
                        self.buttons.guardar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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

function editarLicencia(id) {
    $.confirm({
        title: 'Editar Licencia',
        content: 'url:/admin/tramites/solicitudes/editarLicencia/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/solicitudes/actualizarLicencia',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                    }).fail(function () {
                        self.buttons.guardar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
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