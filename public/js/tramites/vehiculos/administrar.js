$(document).ready(function () {
    obtenerVehiculos();
    obtenerMarcas();
    obtenerClases();
    obtenerCarrocerias();
    obtenerCombustibles();
    obtenerServicios();
    obtenerLineas();
    obtenerTiposBaterias();
});

function verTO(id) {
    $.confirm({
        title: 'Ver Tarjeta de Operación ' + id,
        content: 'url:/admin/tramites/to/ver/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        onContentReady: function () {
            this.$content.find('input, textarea, button, select').attr('disabled', 'disabled');
        },
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function vincularEmpresa(id) {
    $.confirm({
        title: 'Vincular',
        content: 'url:/admin/tramites/vehiculos/vincularEmpresa/' + id,
        buttons: {
            vincular: {
                text: 'Vincular',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/vincularEmpresa',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.vincular.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerVehiculos();
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

function verVinculacion(id) {
    $.confirm({
        title: 'Ver vinculación',
        content: 'url:/admin/tramites/vehiculos/verVinculacion/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        onContentReady: function () {
            this.buttons.guardar.hide();
            this.$content.find('input, textarea, button, select').attr('disabled', 'disabled');
        },
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-warning',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/cambiosVinculacionEmpresa',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.guardar.hide();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerVehiculos();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                    });
                    return false;
                }
            },
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    this.buttons.editar.hide();
                    this.buttons.guardar.show();
                    this.$content.find('input, textarea, button, select').attr('disabled', false);
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

function obtenerServicios() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerServicios',
        }).done(function (data) {
            $('#servicios').find('table').remove();
            $('#servicios').find('.text-center').remove();
            $('#servicios').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#servicios .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#servicios').find('table').remove();
            $('#servicios').find('.text-center').remove();
            $('#servicios').append(data);
        }
    });
});

function editarServicio(id) {
    $.confirm({
        title: 'Editar servicio',
        content: 'url:/admin/tramites/vehiculos/editarServicio/' + id,
        columnClass: 'col-md-10 col-md-offset-1',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarServicio',
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
                        obtenerServicios();
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

function eliminarServicio(id) {
    $.confirm({
        title: 'Eliminar servicio',
        content: 'Está seguro de eliminar esta servicio?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/eliminarServicio/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerServicios();
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

function activarServicio(id) {
    $.confirm({
        title: 'Activar servicio',
        content: 'Está seguro de activar esta servicio?',
        buttons: {
            activar: {
                text: 'Activar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/restaurarServicio/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.activar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerServicios();
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

function nuevoServicio() {
    $.confirm({
        title: 'Nuevo servicio',
        content: 'url:/admin/tramites/vehiculos/nuevoServicio',
        columnClass: 'col-md-10 col-md-offset-1',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevoServicio',
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
                        obtenerServicios();
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

function filtrarVehiculos() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/filtrar/vehiculos/' + $('#filtrarVehiculos').val() + '/' + $('#filtroVehiculos').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#vehiculos').empty().prepend(data);
            } else {
                $('#vehiculos').find('table').remove();
                $('#vehiculos').find('.text-center').remove();
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

function obtenerCarrocerias() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerCarrocerias',
        }).done(function (data) {
            $('#carrocerias').find('table').remove();
            $('#carrocerias').find('.text-center').remove();
            $('#carrocerias').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#carrocerias .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#carrocerias').find('table').remove();
            $('#carrocerias').find('.text-center').remove();
            $('#carrocerias').append(data);
        }
    });
});

function editarCarroceria(id) {
    $.confirm({
        title: 'Editar carroceria',
        content: 'url:/admin/tramites/vehiculos/editarCarroceria/' + id,
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarCarroceria',
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
                        obtenerCarrocerias();
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

function eliminarCarroceria(id) {
    $.confirm({
        title: 'Eliminar carroceria',
        content: 'Está seguro de eliminar esta carroceria?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/eliminarCarroceria/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCarrocerias();
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

function activarCarroceria(id) {
    $.confirm({
        title: 'Activar carroceria',
        content: 'Está seguro de activar esta carroceria?',
        buttons: {
            activar: {
                text: 'Activar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/restaurarCarroceria/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.activar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCarrocerias();
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

function nuevaCarroceria() {
    $.confirm({
        title: 'Nueva carroceria',
        content: 'url:/admin/tramites/vehiculos/nuevaCarroceria',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevaCarroceria',
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
                        obtenerCarrocerias();
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

function obtenerClases() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerClases',
        }).done(function (data) {
            $('#clases').find('table').remove();
            $('#clases').find('.text-center').remove();
            $('#clases').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#clases .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#clases').find('table').remove();
            $('#clases').find('.text-center').remove();
            $('#clases').append(data);
        }
    });
});

function editarClase(id) {
    $.confirm({
        title: 'Editar clase',
        content: 'url:/admin/tramites/vehiculos/editarClase/' + id,
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarClase',
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
                        obtenerClases();
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

function eliminarClase(id) {
    $.confirm({
        title: 'Eliminar clase',
        content: 'Está seguro de eliminar esta clase?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/eliminarClase/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerClases();
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

function activarClase(id) {
    $.confirm({
        title: 'Activar clase',
        content: 'Está seguro de activar esta clase?',
        buttons: {
            activar: {
                text: 'Activar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/restaurarClase/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.activar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerClases();
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

function nuevaClase() {
    $.confirm({
        title: 'Nueva clase',
        content: 'url:/admin/tramites/vehiculos/nuevaClase',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevaClase',
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
                        obtenerClases();
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

function obtenerCombustibles() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerCombustibles',
        }).done(function (data) {
            $('#combustibles').find('table').remove();
            $('#combustibles').find('.text-center').remove();
            $('#combustibles').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#combustibles .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#combustibles').find('table').remove();
            $('#combustibles').find('.text-center').remove();
            $('#combustibles').append(data);
        }
    });
});

function editarCombustible(id) {
    $.confirm({
        title: 'Editar combustible',
        content: 'url:/admin/tramites/vehiculos/editarCombustible/' + id,
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarCombustible',
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
                        obtenerCombustibles();
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

function eliminarCombustible(id) {
    $.confirm({
        title: 'Eliminar combustible',
        content: 'Está seguro de eliminar este combustible?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/eliminarCombustible/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCombustibles();
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

function activarCombustible(id) {
    $.confirm({
        title: 'Activar combustible',
        content: 'Está seguro de activar este combustible?',
        buttons: {
            activar: {
                text: 'Activar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/restaurarCombustible/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.activar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCombustibles();
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

function nuevoCombustible() {
    $.confirm({
        title: 'Nuevo combustible',
        content: 'url:/admin/tramites/vehiculos/nuevoCombustible',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevoCombustible',
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
                        obtenerCombustibles();
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

function obtenerMarcas() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerMarcas',
        }).done(function (data) {
            $('#marcas').find('table').remove();
            $('#marcas').find('.text-center').remove();
            $('#marcas').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#marcas .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#marcas').find('table').remove();
            $('#marcas').find('.text-center').remove();
            $('#marcas').append(data);
        }
    });
});

function editarMarca(id) {
    $.confirm({
        title: 'Editar marca',
        content: 'url:/admin/tramites/vehiculos/editarMarca/' + id,
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarMarca',
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
                        obtenerMarcas();
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

function eliminarMarca(id) {
    $.confirm({
        title: 'Eliminar marca',
        content: 'Está seguro de eliminar esta marca?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/eliminarMarca/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerMarcas();
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

function activarMarca(id) {
    $.confirm({
        title: 'Activar marca',
        content: 'Está seguro de activar esta marca?',
        buttons: {
            activar: {
                text: 'Activar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/restaurarMarca/' + id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.activar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerMarcas();
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

function nuevaMarca() {
    $.confirm({
        title: 'Nueva marca',
        content: 'url:/admin/tramites/vehiculos/nuevaMarca',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevaMarca',
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
                        obtenerMarcas();
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

function obtenerVehiculos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/vehiculos/obtenerVehiculos',
    }).done(function (data) {
        $('#vehiculos').empty().html(data);
    })
}

function nuevoVehiculo() {
    $.confirm({
        title: 'Nuevo vehículo',
        content: 'url:/admin/tramites/vehiculos/nuevoVehiculo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevoVehiculo',
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
                        obtenerVehiculos();
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

function editarVehiculo(id) {
    $.confirm({
        title: 'Editar vehículo',
        content: 'url:/admin/tramites/vehiculos/editarVehiculo/' + id,
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarVehiculo',
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
                        obtenerVehiculos();
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

$(document).on("click", "#vehiculos .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#vehiculos').find('table').remove();
            $('#vehiculos').find('.text-center').remove();
            $('#vehiculos').append(data);
        }
    });
});

function obtenerLineas() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerLineas',
        }).done(function (data) {
            $('#lineas').find('table').remove();
            $('#lineas').find('.text-center').remove();
            $('#lineas').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", "#lineas .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#lineas').find('table').remove();
            $('#lineas').find('.text-center').remove();
            $('#lineas').append(data);
        }
    });
});

function editarLinea(id) {
    $.confirm({
        title: 'Editar línea',
        content: 'url:/admin/tramites/vehiculos/editarLinea/' + id,
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/actualizarLinea',
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
                        obtenerLineas();
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

function nuevaLinea() {
    $.confirm({
        title: 'Nueva línea',
        content: 'url:/admin/tramites/vehiculos/nuevaLinea',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/crearLinea',
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
                        obtenerLineas();
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

var propietarios = null;

function propietariosVehiculo(id) {
    propietarios = $.confirm({
        title: 'Administrar propietarios',
        content: 'url:/admin/tramites/vehiculos/verPropietarios/' + id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function propietariosVehiculoUpdate(id) {
    $.ajax({
        url: '/admin/tramites/vehiculos/verPropietarios/' + id,
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        propietarios.$content.empty().append(response);
    }).fail(function () {
        $.alert({
            title: 'Error de conexión',
            content: 'No se ha podido conectar con el servidor.'
        });
    });
}

function nuevoPropietario(id) {
    $.confirm({
        title: 'Nuevo propietario',
        content: 'url:/admin/tramites/vehiculos/nuevoPropietario/' + id,
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/nuevoPropietario',
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
                        obtenerTiposBaterias();
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

function editarPropietario(idPropietario, idVehiculo) {
    $.confirm({
        title: 'Editar propietario',
        content: 'url:/admin/tramites/vehiculos/editarPropietario/' + idPropietario,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/editarPropietario',
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
                        propietariosVehiculoUpdate(idVehiculo);
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

function retirarPropietario(idPropietario, idVehiculo) {
    $.confirm({
        title: 'Retirar propietario',
        content: 'url:/admin/tramites/vehiculos/retirarPropietario/' + idPropietario + '/' + idVehiculo,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    propietariosVehiculoUpdate(idVehiculo);
                }
            }
        }
    });
}

function vincularPropietario(idPropietario, idVehiculo) {
    $.confirm({
        title: 'Vincular propietario',
        content: 'url:/admin/tramites/vehiculos/vincularPropietario/' + idPropietario + '/' + idVehiculo,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    propietariosVehiculoUpdate(idVehiculo);
                }
            }
        }
    });
}

function obtenerTiposBaterias() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/vehiculos/obtenerTiposBaterias',
        }).done(function (data) {
            $('#tiposBaterias').find('table').remove();
            $('#tiposBaterias').find('.text-center').remove();
            $('#tiposBaterias').append(data);
        })
        .fail(function () {

        });
}

function nuevoTipoBateria() {
    $.confirm({
        title: 'Nuevo tipo bateria',
        content: 'url:/admin/tramites/vehiculos/nuevoTipoBateria',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/crearTipoBateria',
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

function editarTipoBateria(id) {
    $.confirm({
        title: 'Editar Tipo Batería',
        content: 'url:/admin/tramites/vehiculos/editarTipoBateria/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/vehiculos/actualizarTipoBateria',
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
                        obtenerTiposBaterias();
                    }).fail(function () {
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error');
                        obtenerTiposBaterias();
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