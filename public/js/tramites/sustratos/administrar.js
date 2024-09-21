$(document).ready(function () {
    obtenerSustratos();
    obtenerTiposSustratos();
    obtenerMotivosAnulaciones();
    obtenerMotivosLiberaciones();

    $('.datepicker').pickadate({
        selectYears: true,
        selectMonths: true,
        formatSubmit: 'yyyy-mm-dd'
    });

    var fecha = $('#fecha_inicio').val();
    var $inputFecha = $('#fecha_inicio').pickadate();
    var pickerFecha = $inputFecha.pickadate('picker');
    pickerFecha.set('select', fecha, {
        format: 'yyyy-mm-dd'
    });

    var fecha2 = $('#fecha_fin').val();
    var $inputFecha2 = $('#fecha_fin').pickadate();
    var pickerFecha2 = $inputFecha2.pickadate('picker');
    pickerFecha2.set('select', fecha2, {
        format: 'yyyy-mm-dd'
    });
});

function obtenerSustratos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/sustratos/obtenerSustratos',
        dataType: 'html',
        success: function (data) {
            $('#listadoSustratos').empty().html(data);
        }
    });
}

$('#listadoSustratos').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#listadoSustratos').empty().html(data);
        }
    });
});

function nuevosSustratos() {
    $.confirm({
        title: 'Nuevos sustratos',
        content: 'url:/admin/tramites/sustratos/nuevosSustratos',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/sustratos/ingresarSustratos',
                            dataType: 'html',
                            method: 'post',
                            data: this.$content.find('form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.registrar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerSustratos();
                        }).fail(function () {
                            self.buttons.registrar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            'title': 'Error',
                            'content': 'Error en la validación del formulario'
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

function editarSustrato(id) {
    $.confirm({
        title: 'Nuevos sustratos',
        content: 'url:/admin/tramites/sustratos/editarSustrato/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/sustratos/editarSustrato',
                            dataType: 'html',
                            method: 'post',
                            data: this.$content.find('form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.actualizar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerSustratos();
                        }).fail(function () {
                            self.buttons.actualizar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            'title': 'Error',
                            'content': 'Error en la validación del formulario'
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

function obtenerTiposSustratos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/sustratos/obtenerListadoTiposSustratos',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposSustratos').empty().html(data);
    });
}

function nuevoTipoSustrato() {
    $.confirm({
        title: 'Nuevo Tipo Sustrato',
        content: 'url:/admin/tramites/sustratos/nuevoTipoSustrato',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/nuevoTipoSustrato',
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
                        obtenerTiposSustratos();
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

function editarTipoSustrato(id) {
    $.confirm({
        title: 'Editar Tipo Sustrato',
        content: 'url:/admin/tramites/sustratos/editarTipoSustrato/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/editarTipoSustrato',
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
                        obtenerTiposSustratos();
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

function obtenerMotivosAnulaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/sustratos/obtenerListadoMotivosAnulaciones',
        dataType: 'html',
    }).done(function (data) {
        $('#motivosAnulaciones').empty().html(data);
    });
}

function nuevoMotivoAnulacion() {
    $.confirm({
        title: 'Nuevo Motivo Anulación',
        content: 'url:/admin/tramites/sustratos/nuevoMotivoAnulacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/nuevoMotivoAnulacion',
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
                        obtenerMotivosAnulaciones();
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

function editarMotivoAnulacion(id) {
    $.confirm({
        title: 'Editar Motivo Anulación',
        content: 'url:/admin/tramites/sustratos/editarMotivoAnulacion/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/editarMotivoAnulacion',
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
                        obtenerMotivosAnulaciones();
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

function verConsumo(id) {
    $.confirm({
        title: 'Ver consumo',
        content: 'url:/admin/tramites/sustratos/verConsumo/' + id,
        onContentReady: function () {
            this.buttons.confirmarAnulacion.hide();
            this.buttons.confirmarLiberacion.hide();
        },
        buttons: {
            anular: {
                btnClass: 'btn-red',
                text: 'Anular',
                action: function () {
                    var self = this;
                    self.buttons.anular.hide();
                    self.buttons.liberar.hide();
                    $.ajax({
                        url: '/admin/tramites/sustratos/anularSustratoConsumido/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.confirmarAnulacion.show();
                        self.setContent(response);
                    }).fail(function () {
                        self.buttons.anular.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            confirmarAnulacion: {
                btnClass: 'btn-green',
                text: 'Confirmar anulación',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/anularSustratoConsumido',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.confirmarAnulacion.disable();
                        self.setContent(response);
                        obtenerSustratos();
                    }).fail(function () {
                        self.buttons.confirmar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            liberar: {
                btnClass: 'btn-info',
                text: 'Liberar',
                action: function () {
                    var self = this;
                    self.buttons.liberar.hide();
                    self.buttons.anular.hide();
                    $.ajax({
                        url: '/admin/tramites/sustratos/liberarSustratoConsumido/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.confirmarLiberacion.show();
                        self.setContent(response);
                    }).fail(function () {
                        self.buttons.anular.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            confirmarLiberacion: {
                btnClass: 'btn-green',
                text: 'Confirmar liberación',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/liberarSustratoConsumido',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.confirmarLiberacion.disable();
                        self.setContent(response);
                        obtenerSustratos();
                    }).fail(function () {
                        self.buttons.confirmar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
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

function verAnulacion(id) {
    var id = id;
    $.confirm({
        title: 'Ver anulación',
        content: 'url:/admin/tramites/sustratos/verAnulacion/' + id,
        buttons: {
            restaurar: {
                text: '',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/tramites/sustratos/restaurarSustrato/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        self.setContent(data);
                        self.buttons.restaurar.disable;
                        return false;
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    obtenerSustratos();
                }
            }
        }
    });
}

function filtrarSustratos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '/admin/tramites/sustratos/filtrarSustratos',
        data: {
            criterio: $('#criterios').val(),
            parametro: $('#filtrarSustratos').val()
        },
        dataType: 'html',
    }).done(function (data) {
        $('#listadoSustratos').empty().html(data);
    });
}

function obtenerMotivosLiberaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/sustratos/obtenerListadoMotivosLiberaciones',
        dataType: 'html',
    }).done(function (data) {
        $('#motivosLiberaciones').empty().html(data);
    });
}

function nuevoMotivoLiberacion() {
    $.confirm({
        title: 'Nuevo Motivo Liberación',
        content: 'url:/admin/tramites/sustratos/nuevoMotivoLiberacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/nuevoMotivoLiberacion',
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
                        obtenerMotivosLiberaciones();
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

function editarMotivoLiberacion(id) {
    $.confirm({
        title: 'Editar Motivo Liberaación',
        content: 'url:/admin/tramites/sustratos/editarMotivoLiberacion/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/sustratos/editarMotivoLiberacion',
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
                        obtenerMotivosLiberaciones();
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

function verLiberaciones(id) {
    $.confirm({
        title: 'Ver liberaciones',
        content: 'url:/admin/tramites/sustratos/verLiberaciones/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}