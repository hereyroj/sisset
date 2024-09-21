$(document).ready(function(){
    obtenerFotoMultas()
});

function obtenerFotoMultas(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/coactivo/edictos/fotoMultas/obtenerFotoMultas',
    })  .done(function( data ) {
        $('#administrarFotoMultas').find('.listadoFotoMultas').remove();
        $('#administrarFotoMultas').append(data);
    })
        .fail(function(){
            $('#administrarFotoMultas').find('.alert').remove();
            $('#administrarFotoMultas').prepend('<div class="alert alert-danger"><strong>Error:</strong> No se ha procesado la solicitud. Por favor intentelo nuevamente y si el problema persiste contacte a un administrador.</div>');
        });
}

function nuevaNotificacion(){
    $.confirm({
        title: 'Nueva Notificación',
        content: 'url:/admin/coactivo/edictos/fotoMultas/crearFotoMulta',
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
                            url: '/admin/coactivo/edictos/fotoMultas/crearFotoMulta',
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
                            obtenerFotoMultas();
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

function editarFotoMulta(id){
    $.confirm({
        title: 'Nueva Notificación',
        content: 'url:/admin/coactivo/edictos/fotoMultas/cargarFotoMulta/'+id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/coactivo/edictos/fotoMultas/editarFotoMulta',
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
                            obtenerFotoMultas();
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

function eliminarFotoMulta(id){
    $.confirm({
        title: 'Eliminar Notificación',
        content: 'Está seguro de querer eliminar esta notificación?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/coactivo/edictos/fotoMultas/eliminarFotoMulta/'+id,
                        dataType: 'html',
                        method: 'get',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerComparendos();
                    }).fail(function () {
                        self.buttons.eliminar.disable();
                        self.setContent('No se ha podido realizar la acción.');
                        self.setTitle('Error con el servidor');
                    });
                    return false;
                }
            },
            cancelar: {
                text: 'Cancelar',
                action: function () {
                }
            }
        }
    });
}

$('#administrarFotoMultas').on("click", ".pagination li a", function(e){
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function( data ) {
            $('#administrarFotoMultas').empty().html(data);
        }
    });
});

function filtrarBusqueda() {
    if ($('#filtrarBusqueda').val() != '') {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/coactivo/edictos/fotoMultas/filtrarBusqueda/' + $('#filtrarBusqueda').val(),
            success: function (data) {
                $('#administrarFotoMultas').find('.listadoFotoMultas').remove();
                $('#administrarFotoMultas').append(data);
            }
        });
    } else {
        alert('No ha suministrado un criterio válido');
        $('#filtrarBusqueda').focus();
    }
}