var timer = null;
var llamadoTurno = null;
window.turnoActivo = false;
var ventanilla = null;
var activarBoton = null;
@if(auth() - > user() - > hasVentanillaAsignacionActiva())
ventanilla = '{{auth()->user()->hasVentanillaAsignacionActiva()->id}}';
$('#reportes').append('<iframe id="VentanillaSolicitudesAtendidasPorAñosYMeses" src="{{ url('
    admin / reportes / tramites / VentanillaSolicitudesAtendidasPorAñosYMeses / '.auth()->user()->hasVentanillaAsignacionActiva()->id) }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>');
$('#reportes').append('<iframe id="VentanillaYFuncionarioSolicitudesAtentidadasPorAñosYMeses" src="{{ url('
    admin / reportes / tramites / VentanillaYFuncionarioSolicitudesAtentidadasPorAñosYMeses / '.auth()->user()->hasVentanillaAsignacionActiva()->id) }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>');
@endif

$(document).ready(function () {
    obtenerMisTramitesAsignados();
    if (ventanilla != null) {
        timer = setTimeout('llamarTurno()', 60000);
    }
});

function obtenerMisTramitesAsignados() {
    $.ajax({
        url: '/admin/tramites/solicitudes/obtenerMisTramites',
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        $('#mis_tramites').find('table').remove();
        $('#mis_tramites').find('.text-center').remove();
        $('#mis_tramites').append(response);
    }).fail(function () {
        $.alert({
            title: 'Error en la conexión',
            content: 'No se ha podido establecer conexión con el servidor.'
        });
    });
}

function establecerVentanilla() {
    $.confirm({
        title: 'Establecer ventanilla',
        content: 'url:/admin/tramites/solicitudes/establecerVentanilla',
        buttons: {
            establecer: {
                text: 'Establecer',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/solicitudes/establecerVentanilla',
                            dataType: 'json',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            if (response !== undefined) {
                                self.buttons.establecer.hide();
                                self.setTitle('Terminado');
                                self.setContent('Se ha realizado la asignación correctamente a la ventanilla ' + response.name);
                                ventanilla = response.id;
                                $('#reportes').append('<iframe id="VentanillaSolicitudesAtendidasPorAñosYMeses" src="{{ url("admin/reportes/tramites/VentanillaSolicitudesAtendidasPorAñosYMeses/' + ventanilla + '") }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>');
                                $('#reportes').append('<iframe id="VentanillaYFuncionarioSolicitudesAtentidadasPorAñosYMeses" src="{{ url("admin/reportes/tramites/VentanillaYFuncionarioSolicitudesAtentidadasPorAñosYMeses/' + ventanilla + '") }}" height="100%" width="100%" style="height:500px; width:100%; border:none;"></iframe>');
                            } else {
                                self.setTitle('Error');
                                self.setContent('No se ha podido realizar la asignación');
                            }
                        }).fail(function () {
                            self.buttons.establecer.disable();
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

function llamarTurno() {
    clearTimeout(timer);
    if (ventanilla !== null && ventanilla !== undefined) {
        if (window.turnoActivo == false) {
            if (llamadoTurno != null) {
                llamadoTurno.close();
                llamadoTurno = true;
            }
            llamadoTurno = $.confirm({
                title: 'Panel de atención al usuario',
                content: 'url:/admin/tramites/solicitudes/llamarTurno',
                columnClass: 'col-md-12',
                containerFluid: true,
                contentLoaded: function (data) {
                    this.buttons.evaluar.hide();
                    this.buttons.cerrar.hide();
                    this.buttons.finalizar.disable();
                    if (data.indexOf('alert-danger') > -1) {
                        this.buttons.finalizar.hide();
                        timer = setTimeout('cerrarModulo()', 8000);
                    } else {
                        window.turnoActivo = true;
                        setTimeout(function () {
                            llamadoTurno.buttons.finalizar.enable();
                        }, 60000);
                    }
                },
                buttons: {
                    finalizar: {
                        text: 'Finalizar Solicitud',
                        btnClass: 'btn-blue',
                        action: function () {
                            this.buttons.finalizar.hide();
                            var frm = this.$content.find('form');
                            if (frm.parsley().validate()) {
                                var self = this;
                                $.ajax({
                                    url: '/admin/tramites/solicitudes/finalizarTurnoF1/' + frm[0].turno.value + '/' + frm[0].tramite_solicitud.value + '/' + ventanilla,
                                    dataType: 'html',
                                    method: 'get',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                }).done(function (response) {
                                    if (response.indexOf('alert-danger') > -1) {
                                        self.buttons.cerrar.show();
                                    } else {
                                        self.buttons.evaluar.show();
                                    }
                                    self.setContent(response);
                                    self.setTitle('Evaluar servicio');
                                }).fail(function () {
                                    self.buttons.finalizar.hide();
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
                    evaluar: {
                        text: 'Evaluar',
                        btnClass: 'btn-red',
                        action: function () {
                            this.buttons.finalizar.hide();
                            var frm = this.$content.find('form');
                            if (frm.parsley().validate()) {
                                var self = this;
                                $.ajax({
                                    url: '/admin/tramites/solicitudes/finalizarTurnoF2',
                                    dataType: 'html',
                                    method: 'post',
                                    data: frm.serialize(),
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                }).done(function (response) {
                                    if (response.indexOf('alert-danger') > -1) {
                                        self.buttons.cerrar.show();
                                    } else {
                                        self.buttons.evaluar.hide();
                                    }
                                    self.setContent(response);
                                    self.setTitle('Terminado');
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
                        action: function () {
                            cerrarModulo();
                        }
                    }
                }
            });
        }
    } else {
        $.confirm({
            title: 'Advertencia',
            content: 'No tiene una ventanilla asignada',
            buttons: {
                asignar: {
                    text: 'Asignar una ventanilla',
                    btnClass: 'btn-blue',
                    action: function () {
                        establecerVentanilla();
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
}

function llamarOtroTurno() {
    window.turnoActivo = false;
    if (llamadoTurno != null && llamadoTurno != undefined) {
        llamadoTurno.close();
        llamadoTurno = null;
    }
    obtenerMisTramitesAsignados();
    llamarTurno();
}

function cerrarModulo() {
    window.turnoActivo = false;
    if (llamadoTurno != null && llamadoTurno != undefined) {
        llamadoTurno.close();
        llamadoTurno = null;
    }
    obtenerMisTramitesAsignados();
    timer = setTimeout('llamarTurno()', 60000);
}

function solicitarDescanso() {
    $.confirm({
        title: 'Solicitar descanso',
        content: 'url:/admin/tramites/solicitudes/solicitarDescanso',
        autoClose: 'cerrar|60000',
        typeAnimated: true,
        buttons: {
            solicitar: {
                text: 'Solicitar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = self.$content.find('form');
                    $.ajax({
                        url: '/admin/tramites/solicitudes/solicitarDescanso',
                        dataType: 'html',
                        method: 'post',
                        data: frm.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        var mensaje = null;
                        var color = null;
                        if (response != 'NO') {
                            mensaje = 'Se ha registrado el descanso en el sistema. Dispone de ' + response + ' minutos.';
                            color = 'green';
                            timer = setTimeout('llamarTurno()', response * 60000);
                        } else {
                            mensaje = 'No se ha registrado el descanso. Recuerde que no puede exceder a más de 2 en cada media jornada.';
                            color = 'red';
                        }
                        self.setTitle('Resultado');
                        self.setContent(mensaje);
                        self.buttons.solicitar.hide();
                        self.setType(color);
                        return false;
                    }).fail(function () {
                        $.alert({
                            title: 'Error',
                            content: 'No se ha podido registrar el descanso. Intente nuevamente.',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {

                                    }
                                }
                            }
                        });
                        return false;
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

function reLlamarTurno() {
    clearTimeout(timer);
    if (ventanilla !== null && ventanilla !== undefined) {
        if (llamadoTurno != null) {
            llamadoTurno.close();
            llamadoTurno = true;
        }
        $.confirm({
            title: 'Re-llamar Turno',
            content: 'url:/admin/tramites/solicitudes/reLlamarTurno/' + ventanilla,
            containerFluid: true,
            buttons: {
                llamar: {
                    text: 'Llamar',
                    btnClass: 'btn-blue',
                    action: function () {
                        var frm = this.$content.find('form');
                        var ventana = this;
                        if (frm.parsley().validate()) {
                            llamadoTurno = $.confirm({
                                title: 'Re-llamar Turno',
                                content: function () {
                                    var self = this;
                                    return $.ajax({
                                        url: '/admin/tramites/solicitudes/reLlamarTurno',
                                        dataType: 'html',
                                        method: 'post',
                                        data: frm.serialize(),
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    }).done(function (response) {
                                        ventana = null;
                                        frm = null;
                                        self.setContent(response);
                                    }).fail(function () {
                                        self.setContent('Ha ocurrido un error.');
                                        timer = setTimeout('cerrarModulo()', 8000);
                                    });
                                },
                                columnClass: 'col-md-12',
                                containerFluid: true,
                                contentLoaded: function (data) {
                                    this.buttons.evaluar.hide();
                                    this.buttons.cerrar.hide();
                                    this.buttons.finalizar.disable();
                                    if (data.indexOf('alert-danger') > -1) {
                                        this.buttons.finalizar.hide();
                                        timer = setTimeout('cerrarModulo()', 8000);
                                    } else {
                                        window.turnoActivo = true;
                                        setTimeout(function () {
                                            llamadoTurno.buttons.finalizar.enable();
                                        }, 60000);
                                    }
                                },
                                buttons: {
                                    finalizar: {
                                        text: 'Finalizar Solicitud',
                                        btnClass: 'btn-blue',
                                        action: function () {
                                            this.buttons.finalizar.hide();
                                            var frm = this.$content.find('form');
                                            if (frm.parsley().validate()) {
                                                var self = this;
                                                $.ajax({
                                                    url: '/admin/tramites/solicitudes/finalizarTurnoF1/' + frm[0].turno.value + '/' + frm[0].tramite_solicitud.value + '/' + ventanilla,
                                                    dataType: 'html',
                                                    method: 'get',
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    }
                                                }).done(function (response) {
                                                    if (response.indexOf('alert-danger') > -1) {
                                                        self.buttons.cerrar.show();
                                                    } else {
                                                        self.buttons.evaluar.show();
                                                    }
                                                    self.setContent(response);
                                                    self.setTitle('Evaluar servicio');
                                                }).fail(function () {
                                                    self.buttons.finalizar.hide();
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
                                    evaluar: {
                                        text: 'Evaluar',
                                        btnClass: 'btn-red',
                                        action: function () {
                                            this.buttons.finalizar.hide();
                                            var frm = this.$content.find('form');
                                            if (frm.parsley().validate()) {
                                                var self = this;
                                                $.ajax({
                                                    url: '/admin/tramites/solicitudes/finalizarTurnoF2',
                                                    dataType: 'html',
                                                    method: 'post',
                                                    data: frm.serialize(),
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    }
                                                }).done(function (response) {
                                                    if (response.indexOf('alert-danger') > -1) {
                                                        self.buttons.cerrar.show();
                                                    }
                                                    self.buttons.evaluar.hide();
                                                    self.setContent(response);
                                                    self.setTitle('Terminado');
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
                                        action: function () {
                                            cerrarModulo();
                                        }
                                    }
                                }
                            });
                        } else {
                            $.alert({
                                title: 'Error',
                                content: 'Error en la validación de la solicitud.',
                                buttons: {
                                    cerrar: {
                                        text: 'Cerrar',
                                        action: function () {
                                            timer = setTimeout('cerrarModulo()', 8000);
                                        }
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
                        timer = setTimeout('cerrarModulo()', 8000);
                    }
                }
            }
        });
    } else {
        $.confirm({
            title: 'Advertencia',
            content: 'No tiene una ventanilla asignada',
            buttons: {
                asignar: {
                    text: 'Asignar una ventanilla',
                    btnClass: 'btn-blue',
                    action: function () {
                        establecerVentanilla();
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
            url: '/admin/tramites/solicitudes/filtrarMisTramites/' + $('#filtrarSolicitudes').val() + '/' + $('#filtroSolicitudes').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#mis_tramites').find('table').remove();
                $('#mis_tramites').append(data);
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