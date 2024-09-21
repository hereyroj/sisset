$(document).ready(function () {
    obtenerUsuarios();
});

function obtenerUsuarios() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/sistema/usuarios/obtenerUsuarios',
        }).done(function (data) {
            $('#administrarUsuarios').find('table').remove();
            $('#administrarUsuarios').find('.text-center').remove();
            $('#administrarUsuarios').append(data);
        })
        .fail(function () {

        });
}

$(document).on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#administrarUsuarios').find('table').remove();
            $('#administrarUsuarios').find('.text-center').remove();
            $('#administrarUsuarios').append(data);
        }
    });
});

function deshabilitarUsuario(id) {
    $.confirm({
        title: 'Deshabilitar usuario',
        content: function () {
            var self = this;
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{url("/admin/sistema/usuarios/desactivar/")}}' + '/' + id,
                dataType: 'html',
                method: 'get'
            }).done(function (response) {
                self.setContent(response);
                self.setTitle('Deshabilitar usuario');
                obtenerUsuarios();
            }).fail(function () {
                self.setTitle('Deshabilitar usuario');
                self.setContent('No se ha podido realizar la acción.\n Si el problema persiste por favor póngase en contacto con un administrador.');
            });
        },
        columnClass: 'medium',
        backgroundDismiss: false,
        backgroundDismissAnimation: 'glow',
        buttons: {
            cancel: {
                text: 'Cerrar',
                btnClass: 'btn-blue',
                action: function () {

                }
            },
        }
    });
}

function habilitarUsuario(id) {
    $.confirm({
        title: 'Habilitar usuario',
        content: function () {
            var self = this;
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{url("/admin/sistema/usuarios/activar/")}}' + '/' + id,
                dataType: 'html',
                method: 'get'
            }).done(function (response) {
                self.setContent(response);
                self.setTitle('Deshabilitar usuario');
                obtenerUsuarios();
            }).fail(function () {
                self.setTitle('Deshabilitar usuario');
                self.setContent('No se ha podido realizar la acción.\n Si el problema persiste por favor póngase en contacto con un administrador.');
            });
        },
        columnClass: 'medium',
        backgroundDismiss: false,
        backgroundDismissAnimation: 'glow',
        buttons: {
            cancel: {
                text: 'Cerrar',
                btnClass: 'btn-blue',
                action: function () {

                }
            },
        }
    });
}

function eliminarUsuario(id) {
    $.confirm({
        title: 'Eliminar usuario',
        content: function () {
            var self = this;
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{url("/admin/sistema/usuarios/eliminar/")}}' + '/' + id,
                dataType: 'html',
                method: 'get'
            }).done(function (response) {
                self.setContent(response);
                self.setTitle('Deshabilitar usuario');
                obtenerUsuarios();
            }).fail(function () {
                self.setTitle('Deshabilitar usuario');
                self.setContent('No se ha podido realizar la acción.\n Si el problema persiste por favor póngase en contacto con un administrador.');
            });
        },
        columnClass: 'medium',
        backgroundDismiss: false,
        backgroundDismissAnimation: 'glow',
        buttons: {
            cancel: {
                text: 'Cerrar',
                btnClass: 'btn-blue',
                action: function () {

                }
            },
        }
    });
}

function restaurarUsuario(id) {
    $.confirm({
        title: 'Restaurar usuario',
        content: function () {
            var self = this;
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{url("/admin/sistema/usuarios/restaurar/")}}' + '/' + id,
                dataType: 'html',
                method: 'get'
            }).done(function (response) {
                self.setContent(response);
                self.setTitle('Deshabilitar usuario');
                obtenerUsuarios();
            }).fail(function () {
                self.setTitle('Deshabilitar usuario');
                self.setContent('No se ha podido realizar la acción.\n Si el problema persiste por favor póngase en contacto con un administrador.');
            });
        },
        columnClass: 'medium',
        backgroundDismiss: false,
        backgroundDismissAnimation: 'glow',
        buttons: {
            cancel: {
                text: 'Cerrar',
                btnClass: 'btn-blue',
                action: function () {

                }
            },
        }
    });
}

function crearUsuario() {
    $.confirm({
        title: 'Crear usuario',
        content: 'url:/admin/sistema/usuarios/nuevo',
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/usuarios/crear',
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
                            obtenerUsuarios();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso.',
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

function editarUsuario(id) {
    $.confirm({
        title: 'Editar usuario',
        content: 'url:/admin/sistema/usuarios/editar/' + id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/usuarios/editar',
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
                            obtenerUsuarios();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso.',
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

function verPerfil(id) {
    $.confirm({
        title: 'Perfil usuario',
        content: 'url:/admin/sistema/usuarios/perfil/' + id,
        columnClass: 'col-md-12',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                btnClass: 'btn-secondary',
                action: function () {}
            }
        }
    });
}

function convertirEnAgente(id) {
    $.confirm({
        title: 'Convertir en agente',
        content: 'url:/admin/sistema/usuarios/convertirEnAgente/' + id,
        columnClass: 'col-md-4 col-md-offset-4',
        containerFluid: true,
        buttons: {
            convertir: {
                text: 'Convertir',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/usuarios/convertirEnAgente',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.convertir.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerUsuarios();
                        }).fail(function () {
                            self.buttons.convertir.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso.',
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
                btnClass: 'btn-secondary',
                action: function () {}
            }
        }
    });
}

function verAgente(id) {
    $.confirm({
        title: 'Información del Agente',
        content: 'url:/admin/sistema/usuarios/verAgente/' + id,
        columnClass: 'col-md-4 col-md-offset-4',
        containerFluid: true,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                btnClass: 'btn-secondary',
                action: function () {}
            }
        }
    });
}

function desvincularAgente(id) {
    $.confirm({
        title: 'Desvincular agente',
        content: 'url:/admin/sistema/usuarios/desvincularAgente/' + id,
        columnClass: 'col-md-4 col-md-offset-4',
        containerFluid: true,
        buttons: {
            desvincular: {
                text: 'Desvincular',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/sistema/usuarios/desvincularAgente',
                            dataType: 'html',
                            method: 'post',
                            data: frm.serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.desvincular.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerUsuarios();
                        }).fail(function () {
                            self.buttons.desvincular.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en el proceso.',
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
                btnClass: 'btn-secondary',
                action: function () {}
            }
        }
    });
}