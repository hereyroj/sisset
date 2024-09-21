$(document).ready(function () {
    obtenerVigencias();
    obtenerDescuentos();
    obtenerBasesGravables();
    obtenerGruposBaterias();
    obtenerGruposClases();
    obtenerGruposCilindrajes();
});

function obtenerVigencias() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerVigencias',
        }).done(function (data) {
            $('#vigencias').find('table').remove();
            $('#vigencias').find('.text-center').remove();
            $('#vigencias').append(data);
        })
        .fail(function () {

        });
}

function obtenerDescuentos() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerDescuentos',
        }).done(function (data) {
            $('#descuentos').find('table').remove();
            $('#descuentos').find('.text-center').remove();
            $('#descuentos').append(data);
        })
        .fail(function () {

        });
}

$('#descuentos').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#descuentos').find('table').remove();
            $('#descuentos').find('.text-center').remove();
            $('#descuentos').append(data);
        }
    });
});

function obtenerBasesGravables() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerBasesGravables',
        }).done(function (data) {
            $('#avaluos').find('table').remove();
            $('#avaluos').find('.text-center').remove();
            $('#avaluos').append(data);
        })
        .fail(function () {

        });
}

$('#avaluos').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#avaluos').find('table').remove();
            $('#avaluos').find('.text-center').remove();
            $('#avaluos').append(data);
        }
    });
});

function obtenerInfoVehiculo() {
    var placa = $('#placaVehiculo').val();
    if (placa == "" || placa == null || placa == undefined) {
        $.confirm({
            title: 'Información errónea',
            content: 'Debe especificar una placa primero',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    action: function () {
                        $('#placaVehiculo').focus();
                    }
                }
            }
        });
    } else {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerInfoVehiculo/' + placa,
            dataType: 'html'
        }).done(function (data) {
            if (data == undefined || data == '' || data == null) {
                $.confirm({
                    title: 'Sin registros.',
                    content: 'No hay un vehículo registrado con la placa ' + placa,
                    buttons: {
                        cerrar: {
                            text: 'Cerrar',
                            action: function () {
                                $('#placaVehiculo').focus();
                            }
                        }
                    }
                });
            } else {
                $('#infoVehiculo').empty().html(data);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '/admin/tramites/impuestos/obtenerLiquidaciones/' + placa,
                    dataType: 'html'
                }).done(function (data) {
                    $('#liquidacionesVehiculo').empty().html(data);
                });
            }
        });
    }
}

function actualizarLiquidaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/impuestos/obtenerLiquidaciones/' + $('#placaVehiculo').val(),
        dataType: 'html'
    }).done(function (data) {
        $('#liquidacionesVehiculo').empty().html(data);
    });
}

function nuevaLiquidacion(id) {
    $.confirm({
        title: 'Nueva liquidación',
        content: 'url:/admin/tramites/impuestos/nuevaLiquidacion/' + id,
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/nuevaLiquidacion',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerInfoVehiculo();
                    }).fail(function () {
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

function nuevaVigencia() {
    $.confirm({
        title: 'Nueva Vigencia',
        content: 'url:/admin/tramites/impuestos/nuevaVigencia',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/nuevaVigencia',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerVigencias();
                    }).fail(function () {
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

function editarVigencia(id) {
    $.confirm({
        title: 'Editar Vigencia',
        content: 'url:/admin/tramites/impuestos/editarVigencia/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/editarVigencia',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerVigencias();
                    }).fail(function () {
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

function nuevaBaseGravable() {
    $.confirm({
        title: 'Nueva Base Gravable',
        content: 'url:/admin/tramites/impuestos/nuevaBaseGravable',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/crearBaseGravable',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerBasesGravables();
                    }).fail(function () {
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

function editarBaseGravable(id) {
    $.confirm({
        title: 'Editar Base Gravable',
        content: 'url:/admin/tramites/impuestos/editarBaseGravable/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/editarBaseGravable',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerBasesGravables();
                    }).fail(function () {
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

function nuevoDescuento() {
    $.confirm({
        title: 'Nuevo Descuento',
        content: 'url:/admin/tramites/impuestos/nuevoDescuento',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/crearDescuento',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerDescuentos();
                    }).fail(function () {
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

function editarDescuento(id) {
    $.confirm({
        title: 'Editar Descuento',
        content: 'url:/admin/tramites/impuestos/editarDescuento/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/editarDescuento',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerDescuentos();
                    }).fail(function () {
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

function registrarPago(id) {
    $.confirm({
        title: 'Registrar pago',
        content: 'url:/admin/tramites/impuestos/registrarPago/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/registrarPago',
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
                        self.buttons.registar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerInfoVehiculo();
                    }).fail(function () {
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

function verPago(id) {
    $.confirm({
        title: 'Ver pago',
        content: 'url:/admin/tramites/impuestos/verPago/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self1 = this;
                    $.confirm({
                        title: 'Editar pago',
                        content: 'url:/admin/tramites/impuestos/editarPago/' + id,
                        columnClass: 'col-md-6 col-md-offset-3',
                        buttons: {
                            guardar: {
                                text: 'Guardar',
                                btnClass: 'btn-blue',
                                action: function () {
                                    var frm = this.$content.find('form');
                                    var self = this;
                                    $.ajax({
                                        url: '/admin/tramites/impuestos/editarPago',
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
                                        $.ajax({
                                            url: '/admin/tramites/impuestos/verPago/' + id,
                                            dataType: 'html',
                                            method: 'get',
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        }).done(function (response) {
                                            self1.setContent(response);
                                            self1.setTitle('Actualizado');
                                        })
                                    }).fail(function () {
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

function reCalcularLiquidacion(id) {
    $.confirm({
        title: 'Re-calcular liquidación',
        content: 'url:/admin/tramites/impuestos/reCalcularLiquidacion/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/reCalcularLiquidacion',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerInfoVehiculo();
                    }).fail(function () {
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

function importarRegistros() {
    $.confirm({
        title: 'Importar registros',
        content: 'url:/admin/tramites/impuestos/importarRegistros',
        buttons: {
            importar: {
                text: 'Importar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var frm = self.$content.find('form');
                    $.ajax({
                        url: '/admin/tramites/impuestos/importarRegistros',
                        dataType: 'html',
                        method: 'post',
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: new FormData(frm[0]),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.importar.hide();
                        self.setContent(response);
                        self.setTitle('Terminado');
                    }).fail(function () {
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

function obtenerGruposBaterias() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerBateriasGrupos',
        }).done(function (data) {
            $('#gruposBaterias').find('table').remove();
            $('#gruposBaterias').find('.text-center').remove();
            $('#gruposBaterias').append(data);
        })
        .fail(function () {

        });
}

function nuevoGrupoBateria() {
    $.confirm({
        title: 'Nuevo Grupo bateria',
        content: 'url:/admin/tramites/impuestos/nuevaBateriaGrupo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/crearBateriaGrupo',
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
                        obtenerGruposBaterias();
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

function editarGrupoBateria(id) {
    $.confirm({
        title: 'Editar Grupo Batería',
        content: 'url:/admin/tramites/impuestos/editarBateriaGrupo/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/actualizarBateriaGrupo',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.editar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerGruposBaterias();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                        obtenerGruposBaterias();
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

function obtenerGruposClases() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerClasesGrupos',
        }).done(function (data) {
            $('#gruposClases').find('table').remove();
            $('#gruposClases').find('.text-center').remove();
            $('#gruposClases').append(data);
        })
        .fail(function () {

        });
}

function nuevoGrupoClase() {
    $.confirm({
        title: 'Nuevo Grupo Clase',
        content: 'url:/admin/tramites/impuestos/nuevaClaseGrupo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/crearClaseGrupo',
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
                        obtenerGruposClases();
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

function editarGrupoClase(id) {
    $.confirm({
        title: 'Editar Grupo Clase',
        content: 'url:/admin/tramites/impuestos/editarClaseGrupo/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/actualizarClaseGrupo',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.editar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerGruposClases();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                        obtenerGruposClases();
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

function obtenerGruposCilindrajes() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/impuestos/obtenerCilindrajesGrupos',
        }).done(function (data) {
            $('#gruposCilindrajes').find('table').remove();
            $('#gruposCilindrajes').find('.text-center').remove();
            $('#gruposCilindrajes').append(data);
        })
        .fail(function () {

        });
}

function nuevoGrupoCilindraje() {
    $.confirm({
        title: 'Nuevo Grupo Cilindraje',
        content: 'url:/admin/tramites/impuestos/nuevoCilindrajeGrupo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/crearCilindrajeGrupo',
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
                        obtenerGruposCilindrajes();
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

function editarGrupoCilindraje(id) {
    $.confirm({
        title: 'Editar Grupo Cilindraje',
        content: 'url:/admin/tramites/impuestos/editarCilindrajeGrupo/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/impuestos/actualizarCilindrajeGrupo',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.editar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerGruposCilindrajes();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                        obtenerGruposCilindrajes();
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