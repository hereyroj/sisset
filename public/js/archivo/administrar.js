$(document).ready(function () {
    obtenerMosCa();
    obtenerEstadosCarpetas();
});

function multipleCambioEstado() {
    var values = $("input[name='multiple\\[\\]']").map(function () {
        if ($(this).is(':checked')) {
            return $(this).val();
        }
    }).get();
    if (values.length > 0) {
        $.confirm({
            title: 'Cambiando estado...',
            content: 'url:/admin/archivo/multipleCambioEstado',
            buttons: {
                cambiar: {
                    text: 'Cambiar',
                    btnClass: 'btn-primary',
                    action: function () {
                        var self = this;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '/admin/archivo/multipleCambioEstado',
                            data: {
                                data: values,
                                id: self.$content.find('#estadoCarpetaId').val()
                            },
                            dataType: 'html'
                        }).done(function (data) {
                            self.buttons.cambiar.disable();
                            realizarBusqueda();
                            self.setContent(data);
                        }).fail(function () {
                            self.setContent('No se ha podido realizar la operación.');
                        });
                        return false;
                    }
                },
                cerrar: {
                    text: 'Cerrar',
                    btnClass: 'btn-primary',
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

function multipleCambioClase() {
    var values = $("input[name='multiple\\[\\]']").map(function () {
        if ($(this).is(':checked')) {
            return $(this).val();
        }
    }).get();
    if (values.length > 0) {
        $.confirm({
            title: 'Cambiando estado...',
            content: 'url:/admin/archivo/multipleCambioClase',
            buttons: {
                cambiar: {
                    text: 'Cambiar',
                    btnClass: 'btn-primary',
                    action: function () {
                        var self = this;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '/admin/archivo/multipleCambioClase',
                            data: {
                                data: values,
                                id: self.$content.find('#claseVehiculo').val()
                            },
                            dataType: 'html'
                        }).done(function (data) {
                            self.buttons.cambiar.disable();
                            realizarBusqueda();
                            self.setContent(data);
                        }).fail(function () {
                            self.setContent('No se ha podido realizar la operación.');
                        });
                        return false;
                    }
                },
                cerrar: {
                    text: 'Cerrar',
                    btnClass: 'btn-primary',
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

function multipleEliminacion() {
    var values = $("input[name='multiple\\[\\]']").map(function () {
        if ($(this).is(':checked')) {
            return $(this).val();
        }
    }).get();
    if (values.length > 0) {
        $.confirm({
            title: 'Eliminando',
            content: function () {
                var self = this;
                return $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '/admin/archivo/multipleEliminacion',
                    data: {
                        data: values
                    },
                    dataType: 'html'
                }).done(function (data) {
                    realizarBusqueda();
                    self.setContent(data);
                })
            },
            buttons: {
                aceptar: {
                    text: 'Aceptar',
                    btnClass: 'btn-primary',
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

function trasladarCarpeta(id) {
    $.confirm({
        title: 'Trasladar carpeta',
        content: 'url:/admin/archivo/trasladarCarpeta/' + id + '',
        buttons: {
            trasladar: {
                text: 'Trasladar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/trasladarCarpeta',
                        dataType: 'html',
                        method: 'post',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.trasladar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        realizarBusqueda();
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

function cancelarCarpeta(id) {
    $.confirm({
        title: 'Cancelar carpeta',
        content: 'url:/admin/archivo/cancelarCarpeta/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/cancelarCarpeta',
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
                        realizarBusqueda();
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

function historialCarpeta(id) {
    $.confirm({
        title: 'Historial carpeta',
        content: 'url:/admin/archivo/obtenerHistorialCarpeta/' + id,
        columnClass: 'col-md-12',
        onContentReady: function () {
            var data = this.$content.find('table');
            if (data == undefined) {
                this.buttons.exportar.hide();
                this.setContent('<h2>Sin resultados</h2>');
            }
        },
        buttons: {
            exportar: {
                text: 'Exportar',
                btnClass: 'btn-blue',
                action: function () {
                    window.location = '/admin/archivo/exportarHistorialCarpetas/' + id;
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

function verSolicitudPendiente(id) {
    $.confirm({
        title: 'Ver solicitud pendiente',
        content: 'url:/admin/archivo/verSolicitudPendiente/' + id + '',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function realizarBusqueda() {
    if ($('#txtSerie').val() != '' && $('#txtCarpeta').val() != '') {
        $('#carpetas tbody').empty();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: '/admin/archivo/series/' + $('#txtCarpeta').val() + '/' + $('#criterioBusqueda').val(),
            dataType: "html",
            success: function (data) {
                if (data != null && data != undefined) {
                    $('.resultadoBusqueda').empty().html(data);
                    $('.resultadoBusqueda').find('table').floatThead({
                        position: 'fixed',
                    });
                } else {
                    $.confirm({
                        icon: 'glyphicon glyphicon-warning-sign',
                        title: 'Sin resultados!',
                        columnClass: 'medium',
                        content: 'No hay coincidencias en la base de datos con los parámetros indicados.',
                        buttons: {
                            aceptar: {
                                text: 'Aceptar',
                                btnClass: 'btn-primary',
                                action: function () {
                                    $('#txtCarpeta').focus();
                                }
                            }
                        }
                    });
                }
            }
        });
    } else {
        $.confirm({
            icon: 'glyphicon glyphicon-warning-sign',
            title: 'Error!',
            columnClass: 'medium',
            content: 'No ha especificado información válida para la consulta..',
            buttons: {
                aceptar: {
                    text: 'Aceptar',
                    btnClass: 'btn-primary',
                    action: function () {
                        $('#txtCarpeta').focus();
                    }
                }
            }
        });
    }
}

$(document).on('change', '#chk-multiple', function () {
    if (this.checked) {
        $('.resultadoBusqueda table').find('input:checkbox').prop('checked', true);
    } else {
        $('.resultadoBusqueda table').find('input:checkbox').prop('checked', false);
    }
});

function verTraslado(id) {
    $.confirm({
        title: 'Ver traslado',
        content: 'url:/admin/archivo/obtenerTrasladoCarpeta/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        onContentReady: function () {
            var data = this.$content.find('form');
            if (data == undefined) {
                this.buttons.revertir.hide();
                $.confirm({
                    icon: 'glyphicon glyphicon-warning-sign',
                    title: 'Hay un problema!',
                    columnClass: 'medium',
                    content: 'No hay un registro de traslado de carpeta. \n Acciones:',
                    buttons: {
                        trasladar: {
                            text: 'Trasladar',
                            btnClass: 'btn-success',
                            action: function () {
                                trasladarCarpeta(id);
                            }
                        },
                        cambiarestado: {
                            text: 'Cambiar estado',
                            btnClass: 'btn-primary',
                            action: function () {
                                cambiarEstadoCarpeta(id);
                            }
                        },
                        cancel: {
                            text: 'Cancelar',
                            btnClass: 'btn-danger',
                            action: function () {}
                        },
                    }
                });
            }
        },
        buttons: {
            revertir: {
                text: 'Revertir',
                action: function () {
                    $.confirm({
                        title: 'Revertir cancelación',
                        content: 'url:/admin/archivo/revertirTrasladoCarpeta/' + id + '',
                        buttons: {
                            guardar: {
                                text: 'Guardar cambios',
                                btnClass: 'btn-blue',
                                action: function () {
                                    var self = this;
                                    $.ajax({
                                        url: '/admin/archivo/revertirTrasladoCarpeta',
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
                                        realizarBusqueda();
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

function editarCarpeta(id) {
    $.confirm({
        title: 'Editar carpeta',
        content: 'url:/admin/archivo/editarCarpeta/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/editarCarpeta',
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
                        realizarBusqueda();
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

function verCancelacion(id) {
    $.confirm({
        title: 'Ver cancelación',
        content: 'url:/admin/archivo/obtenerCancelacionCarpeta/' + id,
        columnClass: 'col-md-8 col-md-offset-2',
        onContentReady: function () {
            var data = this.$content.find('form');
            if (data == undefined) {
                this.buttons.revertir.hide();
                $.confirm({
                    icon: 'glyphicon glyphicon-warning-sign',
                    title: 'Hay un problema!',
                    backgroundDismiss: false,
                    backgroundDismissAnimation: 'shake',
                    columnClass: 'medium',
                    content: 'No hay un registro de cancelación de la carpeta. \n Acciones:',
                    buttons: {
                        cancelar: {
                            text: 'Cancelar',
                            btnClass: 'btn-secondary',
                            action: function () {
                                cancelarCarpeta(id);
                            }
                        },
                        cambiarestado: {
                            text: 'Cambiar estado',
                            btnClass: 'btn-secondary',
                            action: function () {
                                cambiarEstadoCarpeta(id);
                            }
                        },
                        cerrar: {
                            text: 'Cerrar',
                            btnClass: 'btn-secondary',
                            action: function () {}
                        },
                    }
                });
            }
        },
        buttons: {
            revertir: {
                text: 'Revertir',
                action: function () {
                    $.confirm({
                        title: 'Revertir cancelación',
                        content: 'url:/admin/archivo/revertirCancelacionCarpeta/' + id + '',
                        buttons: {
                            guardar: {
                                text: 'Guardar cambios',
                                btnClass: 'btn-blue',
                                action: function () {
                                    var self = this;
                                    $.ajax({
                                        url: '/admin/archivo/revertirCancelacionCarpeta',
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
                                        realizarBusqueda();
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

function obtenerMosCa() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/archivo/obtenerMosCa',
        dataType: 'html',
        success: function (data) {
            $('#motivos_cancelacion').empty().html(data);
        }
    });
}

$('#motivos_cancelacion').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#motivos_cancelacion').empty().html(data);
        }
    });
});

function eliminarMosCa(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/archivo/eliminarMosCa/' + id,
        dataType: 'html',
        success: function (data) {
            obtenerMosCa();
        }
    });
}

function nuevoMosCa() {
    $.confirm({
        title: 'Crear motivo cancelación',
        content: 'url:/admin/archivo/crearMotivoCancelacion',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/crearMotivoCancelacion',
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
                        obtenerMosCa();
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

function editarMosCa(id) {
    $.confirm({
        title: 'Editar motivo cancelación',
        content: 'url:/admin/archivo/editarMotivoCancelacion/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/editarMotivoCancelacion',
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
                        obtenerMosCa();
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

function obtenerEstadosCarpetas() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/archivo/obtenerEstadosCarpeta',
        dataType: 'html',
        success: function (data) {
            $('#estados_carpeta').empty().html(data);
        }
    });
}

function nuevoEstadoCarpeta() {
    $.confirm({
        title: 'Crear estado carpeta',
        content: 'url:/admin/archivo/crearEstadoCarpeta',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/crearEstadoCarpeta',
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
                        obtenerEstadosCarpetas();
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

function editarEstadoCarpeta(id) {
    $.confirm({
        title: 'Editar estado carpeta',
        content: 'url:/admin/archivo/editarEstadoCarpeta/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/editarEstadoCarpeta',
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
                        obtenerEstadosCarpetas();
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

function cambiarEstadoCarpeta(id) {
    $.confirm({
        title: 'Cambiar estado carpeta',
        content: 'url:/admin/archivo/cambiarEstadoCarpeta/' + id + '',
        buttons: {
            guardar: {
                text: 'Guardar cambios',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/cambiarEstadoCarpeta',
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
                        realizarBusqueda();
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

function importarRegistros() {
    $.confirm({
        title: 'Importar registros',
        content: 'url:/admin/archivo/importarRegistros',
        buttons: {
            importar: {
                text: 'Importar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    var formData = new FormData(this.$content.find('form')[0]);
                    $.ajax({
                        url: '/admin/archivo/importarRegistros',
                        dataType: 'html',
                        method: 'post',
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.importar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
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

function crearCarpeta() {
    $.confirm({
        title: 'Crear carpeta',
        content: 'url:/admin/archivo/crearCarpeta',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/crearCarpeta',
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
                        realizarBusqueda();
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

function crearMultiplesCarpetas() {
    $.confirm({
        title: 'Crear carpeta',
        content: 'url:/admin/archivo/crearMultiplesCarpetas',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/archivo/crearMultiplesCarpetas',
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
                        realizarBusqueda();
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