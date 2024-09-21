$(document).ready(function () {
    obtenerComparendos();
    obtenerInfracciones();
    obtenerTiposComparendos();
    obtenerTiposInmovilizaciones();
    obtenerTiposInfractores();
    obtenerLicenciaCategorias();
    obtenerEntidades();
});

function obtenerComparendos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerComparendos',
        dataType: 'html',
        success: function (data) {
            $('#listadoComparendos').empty().html(data);
        }
    });
}

$('#listadoComparendos').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#listadoComparendos').empty().html(data);
        }
    });
});

function nuevoComparendo() {
    $.confirm({
        title: 'Nuevo comparendo',
        content: 'url:/admin/inspeccion/comparendos/nuevo',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/nuevo',
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
                            self.setContent(response);
                            self.setTitle('Terminado');
                            $('div.jconfirm-scrollpane').scrollTop(0);
                            obtenerComparendos();
                        }).fail(function () {
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

function verAgente(id) {
    $.confirm({
        title: 'Información del agente',
        content: 'url:/admin/inspeccion/comparendos/verAgente/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verVehiculo(id) {
    $.confirm({
        title: 'Información del vehículo',
        content: 'url:/admin/inspeccion/comparendos/verVehiculo/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verInfractor(id) {
    $.confirm({
        title: 'Información del infractor',
        content: 'url:/admin/inspeccion/comparendos/verInfractor/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verPago(id) {
    $.confirm({
        title: 'Información del pago',
        content: 'url:/admin/inspeccion/comparendos/verPago/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function registrarPago(id) {
    $.confirm({
        title: 'Registrar pago',
        content: 'url:/admin/inspeccion/comparendos/registrarPago/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/registrarPago',
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
                            self.buttons.registrar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerComparendos();
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

function editarComparendo(id) {
    $.confirm({
        title: 'Editar comparendo',
        content: 'url:/admin/inspeccion/comparendos/editarComparendo/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/editarComparendo',
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
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerComparendos();
                        }).fail(function () {
                            self.buttons.guardar.disable();
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

function editarPago(id) {
    $.confirm({
        title: 'Editar pago',
        content: 'url:/admin/inspeccion/comparendos/editarPago/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/editarPago',
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
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerComparendos();
                        }).fail(function () {
                            self.buttons.guardar.disable();
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

function obtenerInfracciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerListadoInfracciones',
        dataType: 'html',
    }).done(function (data) {
        $('#infracciones').empty().html(data);
    });
}

function nuevaInfraccion() {
    $.confirm({
        title: 'Nueva infracción',
        content: 'url:/admin/inspeccion/comparendos/nuevaInfraccion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/nuevaInfraccion',
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
                        obtenerInfracciones();
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

function editarInfraccion(id) {
    $.confirm({
        title: 'Editar infracción',
        content: 'url:/admin/inspeccion/comparendos/editarInfraccion/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/editarInfraccion',
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
                        obtenerInfracciones();
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

function obtenerTiposComparendos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerListadoTiposComparendos',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposComparendos').empty().html(data);
    });
}

function nuevoTipoComparendo() {
    $.confirm({
        title: 'Nuevo Tipo Comparendo',
        content: 'url:/admin/inspeccion/comparendos/nuevoTipoComparendo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/nuevoTipoComparendo',
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
                        obtenerTiposComparendos();
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

function editarTipoComparendo(id) {
    $.confirm({
        title: 'Editar Tipo Comparendo',
        content: 'url:/admin/inspeccion/comparendos/editarTipoComparendo/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/editarTipoComparendo',
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
                        obtenerTiposComparendos();
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

function obtenerTiposInmovilizaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerListadoTiposInmovilizaciones',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposInmovilizaciones').empty().html(data);
    });
}

function nuevoTipoInmovilizacion() {
    $.confirm({
        title: 'Nuevo Tipo Inmovilizacion',
        content: 'url:/admin/inspeccion/comparendos/nuevoTipoInmovilizacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/nuevoTipoInmovilizacion',
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
                        obtenerTiposInmovilizaciones();
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

function editarTipoInmovilizacion(id) {
    $.confirm({
        title: 'Editar Tipo Inmovilizacion',
        content: 'url:/admin/inspeccion/comparendos/editarTipoInmovilizacion/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/editarTipoInmovilizacion',
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
                        obtenerTiposInmovilizaciones();
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

function selectAll(e) {
    if (e.checked) {
        $('#listadoComparendos table').find('input:checkbox').prop('checked', true);
    } else {
        $('#listadoComparendos table').find('input:checkbox').prop('checked', false);
    }
}

function sancionarComparendos() {
    $.confirm({
        title: 'Sancionar comparendos',
        content: 'url:/admin/inspeccion/comparendos/sancionar',
        buttons: {
            sancionar: {
                text: 'Sancionar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/inspeccion/comparendos/sancionar',
                        data: self.$content.find('form').serialize(),
                        dataType: 'html'
                    }).done(function (data) {
                        self.buttons.sancionar.disable();
                        obtenerComparendos();
                        self.setContent(data);
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la operación.');
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

function sancionarComparendosSeleccionados() {
    var values = $("input[name='comparendos\\[\\]']").map(function () {
        if ($(this).is(':checked')) {
            return $(this).val();
        }
    }).get();
    if (values.length > 0) {
        $.confirm({
            title: 'Sancionar comparendos',
            content: 'url:/admin/inspeccion/comparendos/sancionar',
            buttons: {
                sancionar: {
                    text: 'Sancionar',
                    btnClass: 'btn-primary',
                    action: function () {
                        var self = this;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '/admin/inspeccion/comparendos/sancionarSeleccionados',
                            data: {
                                data: values,
                                id: self.$content.find('#tipoSancion').val()
                            },
                            dataType: 'html'
                        }).done(function (data) {
                            self.buttons.sancionar.disable();
                            obtenerComparendos();
                            self.setContent(data);
                        }).fail(function () {
                            self.setContent('No se ha podido realizar la operación.');
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
    } else {
        $.confirm({
            icon: 'glyphicon glyphicon-warning-sign',
            title: 'Sin selección!',
            columnClass: 'medium',
            content: 'No se han seleccionado elementos.',
            buttons: {
                aceptar: {
                    text: 'Aceptar',
                    btnClass: 'btn-primary',
                    action: function () {
                        $('#chk-multiple').focus();
                    }
                }
            }
        });
    }
}

function obtenerTiposInfractores() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerTiposInfractores',
        dataType: 'html',
        success: function (data) {
            $('#tiposInfractores').empty().html(data);
        }
    });
}

$('#tiposInfractores').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#tiposInfractores').empty().html(data);
        }
    });
});

function nuevoTipoInfractor() {
    $.confirm({
        title: 'Nuevo tipo infractor',
        content: 'url:/admin/inspeccion/comparendos/nuevoTipoInfractor',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/crearTipoInfractor',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposInfractores();
                        }).fail(function () {
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

function editarTipoInfractor(id) {
    $.confirm({
        title: 'Editar tipo infractor',
        content: 'url:/admin/inspeccion/comparendos/editarTipoInfractor/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/actualizarTipoInfractor',
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
                        obtenerTiposInfractores();
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

function verInmovilizacion(id) {
    $.confirm({
        title: 'Información de la inmovilización',
        content: 'url:/admin/inspeccion/comparendos/verInmovilizacion/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verUbicacion(id) {
    $.confirm({
        title: 'Información de la ubicación',
        content: 'url:/admin/inspeccion/comparendos/verUbicacion/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function verTestigo(id) {
    $.confirm({
        title: 'Información del testigo',
        content: 'url:/admin/inspeccion/comparendos/verTestigo/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function obtenerLicenciaCategorias() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerLicenciaCategorias',
        dataType: 'html',
        success: function (data) {
            $('#listadoLicenciaCategorias').empty().html(data);
        }
    });
}

function nuevaLicenciaCategoria() {
    $.confirm({
        title: 'Nueva Licencia Categoría',
        content: 'url:/admin/inspeccion/comparendos/nuevaLicenciaCategoria',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/crearLicenciaCategoria',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerLicenciaCategorias();
                        }).fail(function () {
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

function editarLicenciaCategoria(id) {
    $.confirm({
        title: 'Editar tipo via',
        content: 'url:/admin/inspeccion/comparendos/editarLicenciaCategoria/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/actualizarLicenciaCategoria',
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
                        obtenerLicenciaCategorias();
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

function obtenerEntidades() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/comparendos/obtenerEntidades',
        dataType: 'html',
        success: function (data) {
            $('#listadoEntidades').empty().html(data);
        }
    });
}

function nuevaEntidad() {
    $.confirm({
        title: 'Nueva Entidad',
        content: 'url:/admin/inspeccion/comparendos/nuevaEntidad',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/inspeccion/comparendos/crearEntidad',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerEntidades();
                        }).fail(function () {
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

function editarEntidad(id) {
    $.confirm({
        title: 'Editar Entidad',
        content: 'url:/admin/inspeccion/comparendos/editarEntidad/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/inspeccion/comparendos/actualizarEntidad',
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
                        obtenerEntidades();
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

function filtrarComparendos() {
    if ($('#filtrarComparendos').val() == undefined) {
        $.alert({
            title: 'Error!',
            content: 'No se ha especificado el valor de búsqueda.',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    action: function () {
                        $('#filtrarComparendos').focus();
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
            url: '/admin/inspeccion/comparendos/filtrar/' + $('#filtrarComparendos').val() + '/' + $('#filtroComparendos').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#listadoComparendos').empty().html(data);
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