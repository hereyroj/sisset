function filtrarSinAprobar() {
    if ($('#filtrarSinAprobar').val() == "" || $('#criteriosSinAprobar').val() == "") {
        $.alert({
            title: 'Error!',
            theme: 'light',
            type: 'red',
            typeAnimated: true,
            content: 'No se ha especificado el parámetro de búsqueda correctamente.'
        });
    } else {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/solicitudes/administracion/filtro/sinAprobar/' + $('#filtrarSinAprobar').val() + '/' + $('#criteriosSinAprobar').val(),
                dataType: "html"
            }).done(function (data) {
                $('#sinAprobar').find('table').remove();
                $('#sinAprobar').find('.text-center').remove();
                $('#sinAprobar').append(data);
            })
            .fail(function () {
                $.alert({
                    title: 'Error!',
                    theme: 'light',
                    type: 'red',
                    content: 'No se ha podido establecer una conexión con el servidor.'
                });
            });
    }
}

function filtrarSinDevolver() {
    if ($('#filtrarSinDevolver').val() == "" || $('#criteriosSinDevolver').val() == "") {
        $.alert({
            title: 'Error!',
            theme: 'light',
            type: 'red',
            content: 'No se ha especificado el parámetro de búsqueda correctamente.'
        });
    } else {
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/solicitudes/administracion/filtro/sinDevolver/' + $('#filtrarSinDevolver').val() + '/' + $('#criteriosSinDevolver').val(),
                dataType: "html"
            }).done(function (data) {
                $('#sinDevolver').find('table').remove();
                $('#sinDevolver').find('.text-center').remove();
                $('#sinDevolver').append(data);
            })
            .fail(function () {
                $.alert({
                    title: 'Error!',
                    theme: 'light',
                    type: 'red',
                    content: 'No se ha podido establecer una conexión con el servidor.'
                });
            });
    }
}

$(document).ready(function () {
    solicitudesSinAprobar();
    solicitudesSinDevolver();
    obtenerMotivosSolicitud();
    obtenerMotivosDenegacion();
});

function solicitudesSinAprobar() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/sinAprobar',
            dataType: "html"
        }).done(function (data) {
            $('#sinAprobar').find('table').remove();
            $('#sinAprobar').find('.text-center').remove();
            $('#sinAprobar').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                theme: 'light',
                type: 'red',
                content: 'No se ha podido establecer una conexión con el servidor.'
            });
        });
}

function aprobarSolicitud(id) {
    $.confirm({
        title: 'Aprobando solicitud...',
        content: function () {
            var self = this;
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/solicitudes/administracion/aprobarSolicitud/' + id,
                dataType: 'html'
            }).done(function (data) {
                solicitudesSinAprobar();
                self.setContent(data);
            }).fail(function () {
                self.setContent('<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>');
            })
        },
        buttons: {
            aceptar: {
                text: 'Aceptar',
                btnClass: 'btn-primary',
                action: function () {}
            }
        }
    });
}

function ingresarCarpeta(id) {
    $.confirm({
        title: 'Aprobando solicitud...',
        content: function () {
            var self = this;
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '/admin/solicitudes/administracion/ingresarCarpeta/' + id,
                dataType: 'html'
            }).done(function (data) {
                solicitudesSinDevolver();
                self.setContent(data);
            }).fail(function () {
                self.setContent('<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor inténtelo nuevamente y si el problema persiste contacte a un administrador.</div>');
            })
        },
        buttons: {
            aceptar: {
                text: 'Aceptar',
                btnClass: 'btn-primary',
                action: function () {}
            }
        }
    });
}

function solicitudesSinDevolver() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/sinDevolver',
            dataType: "html"
        }).done(function (data) {
            $('#sinDevolver').find('table').remove();
            $('#sinDevolver').find('.text-center').remove();
            $('#sinDevolver').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                theme: 'light',
                type: 'red',
                content: 'No se ha podido establecer una conexión con el servidor.'
            });
        });
}

function denegarSolicitud(id) {
    $.confirm({
        title: 'Denegar solicitud',
        content: 'url:/admin/solicitudes/administracion/denegarSolicitud/' + id,
        buttons: {
            denegar: {
                text: 'Denegar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    var form = this.$content.find('form');
                    $.ajax({
                        url: '/admin/solicitudes/administracion/denegarSolicitud',
                        dataType: 'html',
                        method: 'post',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.denegar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        solicitudesSinAprobar();
                    }).fail(function () {
                        self.buttons.denegar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            cancelar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function obtenerMotivosSolicitud() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/obtenerMotivosSolicitud'
        }).done(function (data) {
            $('#motivosSolicitud').empty().html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                theme: 'light',
                type: 'red',
                content: 'No se ha podido establecer una conexión con el servidor.'
            });
        });
}

function nuevoMotivoSolicitud() {
    $.confirm({
        title: 'Crear motivo solicitud',
        content: 'url:/admin/solicitudes/administracion/nuevoMotivoSolicitud',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/solicitudes/administracion/crearMotivoSolicitud',
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
                        obtenerMotivosSolicitud();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function editarMotivoSolicitud(id) {
    $.confirm({
        title: 'Editar motivo solicitud',
        content: 'url:/admin/solicitudes/administracion/editarMotivoSolicitud/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/solicitudes/administracion/editarMotivoSolicitud',
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
                        obtenerMotivosSolicitud();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function obtenerMotivosDenegacion() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/solicitudes/administracion/obtenerMotivosDenegacion'
        }).done(function (data) {
            $('#motivosDenegacion').empty().html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error!',
                theme: 'light',
                type: 'red',
                content: 'No se ha podido establecer una conexión con el servidor.'
            });
        });
}

function nuevoMotivoDenegacion() {
    $.confirm({
        title: 'Crear motivo denegación',
        content: 'url:/admin/solicitudes/administracion/nuevoMotivoDenegacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/solicitudes/administracion/crearMotivoDenegacion',
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
                        obtenerMotivosDenegacion();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function editarMotivoDenegacion(id) {
    $.confirm({
        title: 'Editar motivo denegación',
        content: 'url:/admin/solicitudes/administracion/editarMotivoDenegacion/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/solicitudes/administracion/editarMotivoDenegacion',
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
                        obtenerMotivosDenegacion();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}