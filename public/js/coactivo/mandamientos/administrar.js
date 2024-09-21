$(document).ready(function(){
    obtenerMandamientos();
    obtenerMediosNotificacion();
    obtenerMotivosDevolucion();
    obtenerTiposFinalizacion();
    obtenerTiposNotificacion();
});

function obtenerMandamientos(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/coactivo/mandamientos/obtenerListadoMandamientos',
    })  .done(function( data ) {
        $('#administrarMandamientos').empty().html(data);
    })
        .fail(function(){
            
        });
}

function obtenerMediosNotificacion(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/coactivo/mandamientos/obtenerListadoMediosNotificacion',
    })  .done(function( data ) {
        $('#mediosNotificacion').empty().html(data);
    })
        .fail(function(){
            
        });
}

function obtenerMotivosDevolucion(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/coactivo/mandamientos/obtenerListadoMotivosDevolucion',
    })  .done(function( data ) {
        $('#motivosDevolucion').empty().html(data);
    })
        .fail(function(){
            
        });
}

function obtenerTiposFinalizacion(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/coactivo/mandamientos/obtenerListadoTiposFinalizacion',
    })  .done(function( data ) {
        $('#tiposFinalizacion').empty().html(data);
    })
        .fail(function(){
            
        });
}

function obtenerTiposNotificacion(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/coactivo/mandamientos/obtenerListadoTiposNotificacion',
    })  .done(function( data ) {
        $('#tiposNotificacion').empty().html(data);
    })
        .fail(function(){
            
        });
}

function nuevoMandamiento(){
    $.confirm({
        title: 'Nuevo Mandamiento',
        content: 'url:/admin/coactivo/mandamientos/nuevoMandamiento',
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
                            url: '/admin/coactivo/mandamientos/crearMandamiento',
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
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMandamientos();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function editarMandamiento(id){
    $.confirm({
        title: 'Editar Mandamiento',
        content: 'url:/admin/coactivo/mandamientos/editarMandamiento/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/actualizarMandamiento',
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
                            obtenerMandamientos();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

$('#administrarMandamientos').on("click", ".pagination li a", function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function( data ) {
            $('#administrarMandamientos').empty().html(data);
        }
    });
});

function nuevoTipoNotificacion(){
    $.confirm({
        title: 'Nuevo Tipo Notificación',
        content: 'url:/admin/coactivo/mandamientos/nuevoTipoNotificacion',
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
                            url: '/admin/coactivo/mandamientos/crearTipoNotificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposNotificacion();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function editarTipoNotificacion(id){
    $.confirm({
        title: 'Editar Tipo Notificación',
        content: 'url:/admin/coactivo/mandamientos/editarTipoNotificacion/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/actualizarTipoNotificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposNotificacion();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function nuevoTipoFinalizacion(){
    $.confirm({
        title: 'Nuevo Tipo Finalización',
        content: 'url:/admin/coactivo/mandamientos/nuevoTipoFinalizacion',
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
                            url: '/admin/coactivo/mandamientos/crearTipoFinalizacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposFinalizacion();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function editarTipoFinalizacion(id){
    $.confirm({
        title: 'Editar Tipo Finalización',
        content: 'url:/admin/coactivo/mandamientos/editarTipoFinalizacion/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/actualizarTipoFinalizacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposFinalizacion();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function nuevoMedioNotificacion(){
    $.confirm({
        title: 'Nuevo Medio Notificación',
        content: 'url:/admin/coactivo/mandamientos/nuevoMedioNotificacion',
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
                            url: '/admin/coactivo/mandamientos/crearMedioNotificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMediosNotificacion();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function editarMedioNotificacion(id){
    $.confirm({
        title: 'Editar Medio Notificación',
        content: 'url:/admin/coactivo/mandamientos/editarMedioNotificacion/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/actualizarMedioNotificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMediosNotificacion();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function nuevoTipoNotificacion(){
    $.confirm({
        title: 'Nuevo Tipo Notificación',
        content: 'url:/admin/coactivo/mandamientos/nuevoTipoNotificacion',
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
                            url: '/admin/coactivo/mandamientos/crearTipoNotificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposNotificacion();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function editarTipoNotificacion(id){
    $.confirm({
        title: 'Editar Tipo Notificación',
        content: 'url:/admin/coactivo/mandamientos/editarTipoNotificacion/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/actualizarTipoNotificacion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerTiposNotificacion();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function nuevoMotivoDevolucion(){
    $.confirm({
        title: 'Nuevo Motivo Devolución',
        content: 'url:/admin/coactivo/mandamientos/nuevoMotivoDevolucion',
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
                            url: '/admin/coactivo/mandamientos/crearMotivoDevolucion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMotivosDevolucion();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function editarMotivoDevolucion(id){
    $.confirm({
        title: 'Editar Motivo Devolución',
        content: 'url:/admin/coactivo/mandamientos/editarMotivoDevolucion/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/actualizarMotivoDevolucion',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.guardar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMotivosDevolucion();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function nuevaNotificacion(id){
    $.confirm({
        title: 'Nueva Notificación',
        content: 'url:/admin/coactivo/mandamientos/nuevaNotificacion/'+id,
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
                            url: '/admin/coactivo/mandamientos/crearNotificacion',
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
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            recargarListadoNotificaciones(id);
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

var ln = null;

function verNotificaciones(id){
    ln = $.confirm({
        title: 'Ver Notificaciones',
        content: 'url:/admin/coactivo/mandamientos/obtenerListadoNotificaciones/'+id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function recargarListadoNotificaciones(id){
    $.ajax({
        url: '/admin/coactivo/mandamientos/obtenerListadoNotificaciones/'+id,
        dataType: 'html',
        method: 'get',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function (response) {
        ln.$content.empty();
        ln.setContent(response);
    });      
}

function registrarFinalizacion(id){
    $.confirm({
        title: 'Registrar Finalización',
        content: 'url:/admin/coactivo/mandamientos/nuevaFinalizacion/'+id,
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
                            url: '/admin/coactivo/mandamientos/crearFinalizacion',
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
                            self.buttons.crear.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMandamientos();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function verFinalizacion(id){
    var idFinalizacion = id;
    $.confirm({
        title: 'Ver Finalización',
        content: 'url:/admin/coactivo/mandamientos/obtenerFinalizacion/'+id,
        columnClass: 'col-md-6 col-md-offset-3',
        onContentReady: function () {
            this.buttons.guardar.hide();
        },
        buttons: {
            editar: {
                    text: 'Editar',
                    btnClass: 'btn-blue',
                    action: function (){
                        var self = this;
                        $.ajax({
                                url: '/admin/coactivo/mandamientos/editarFinalizacion/'+idFinalizacion,
                                dataType: 'html',
                                method: 'get',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            }).done(function (response) {
                                self.buttons.editar.hide();
                                self.buttons.guardar.show();
                                self.$content.empty();
                                self.setContent(response);
                            }).fail(function () {
                                self.setContent('No se ha podido realizar la acción.');
                                self.setTitle('Error con el servidor');
                            });
                        return false;
                    }
                },
                guardar: {
                    text: 'Guardar',
                    btnClass: 'btn-blue',
                    action: function () {
                        var frm = this.$content.find('form');
                        if (frm.parsley().validate()) {
                            var self = this;
                            $.ajax({
                                url: '/admin/coactivo/mandamientos/actualizarFinalizacion',
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
                                obtenerMandamientos();
                            }).fail(function () {
                                self.buttons.guardar.disable();
                                self.setContent('No se ha podido realizar la acción.');
                                self.setTitle('Error con el servidor');
                            });
                            return false;
                        } else {
                            $.alert({
                                title: 'Error',
                                content: 'Error en la respuesta del proceso.',
                                buttons: {
                                    cerrar: {
                                        text: 'Cerrar',
                                        action: function () {
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
                }
            }
        }
    });
}

function vincularComparendo(id){
    $.confirm({
        title: 'Vincular comparendo',
        content: 'url:/admin/coactivo/mandamientos/vincularComparendo/'+id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            vincular: {
                text: 'Vincular',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/mandamientos/vincularComparendo',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.vincular.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerMandamientos();
                        }).fail(function () {
                            self.buttons.vincular.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la respuesta del proceso.',
                            buttons: {
                                cerrar: {
                                    text: 'Cerrar',
                                    action: function () {
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
                }
            }
        }
    });
}

function verComparendo(id){
    ln = $.confirm({
        title: 'Información del comparendo',
        content: 'url:/admin/coactivo/mandamientos/verComparendo/'+id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function verSancion(id){
    $.confirm({
        title: 'Sanción del comparendo',
        content: 'url:/admin/coactivo/mandamientos/verSancion/'+id,
        columnClass: 'col-md-6',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function verAcuerdoPago(id){
    ln = $.confirm({
        title: 'Información del acuerdo de pago',
        content: 'url:/admin/coactivo/mandamientos/verAcuerdoPago/'+id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function verProcesoAcuerdoPago(id){
    ln = $.confirm({
        title: 'Información del Proceso Acuerdo de Pago',
        content: 'url:/admin/coactivo/mandamientos/verProcesoAcuerdoPago/'+id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function verPago(id) {
    $.confirm({
        title: 'Información del pago',
        content: 'url:/admin/coactivo/mandamientos/verPago/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                }
            }
        }
    });
}

function registrarPago(id) {
    $.confirm({
        title: 'Registrar pago',
        content: 'url:/admin/coactivo/mandamientos/registrarPago/' + id,
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
                            url: '/admin/coactivo/mandamientos/registrarPago',
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
                action: function () {
                }
            }
        }
    });
}

function editarPago(id) {
    $.confirm({
        title: 'Editar pago',
        content: 'url:/admin/coactivo/mandamientos/editarPago/' + id,
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
                            url: '/admin/coactivo/mandamientos/editarPago',
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
                action: function () {
                }
            }
        }
    });
}

function filtrarMandamientos(){
    if($('#filtrarMandamientos').val() == undefined){
        $.alert({
            title: 'Error!',
            content: 'No se ha especificado el valor de búsqueda.',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    action: function () {
                        $('#filtrarMandamientos').focus();
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
        url: '/admin/coactivo/mandamientos/filtrar/' + $('#filtrarMandamientos').val() + '/' + $('#filtroMandamientos').val(),
    }).done(function (data) {
        if (data != null && data != undefined && data != '') {
            $('#administrarMandamientos').empty().prepend(data);
        } else {
            $.alert({
                title: 'Error!',
                content: 'No se ha encontrado registros con la información suministrada.',
                buttons: {
                    cerrar: {
                        text: 'Cerrar',
                        action: function () {
                        }
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
                        action: function () {
                        }
                    }
                }
            });
        });
}