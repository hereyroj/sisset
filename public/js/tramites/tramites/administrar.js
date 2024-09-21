$(document).ready(function () {
    obtenerTramites();
    obtenerGrupos();
});

function obtenerTramites() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/tramites/obtenerTramites',
        }).done(function (data) {
            $('#tramites').find('table').remove();
            $('#tramites').find('.text-center').remove();
            $('#tramites').append(data);
        })
        .fail(function () {

        });
}

function obtenerGrupos() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/tramites/tramitesGrupos/obtenerGrupos',
        }).done(function (data) {
            $('#grupos').find('table').remove();
            $('#grupos').find('.text-center').remove();
            $('#grupos').append(data);
        })
        .fail(function () {

        });
}

function verRequerimientos(id) {
    $.confirm({
        title: 'Requerimientos del tramite',
        content: 'url:/admin/tramites/tramites/administrarRequerimientos/' + id,
        columnClass: 'col-md-12',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function editarTramite(id) {
    $.confirm({
        title: 'Editar tramite',
        content: 'url:/admin/tramites/tramites/editarTramite/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/tramites/actualizarTramite',
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
                            obtenerTramites();
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

function editarGrupo(id) {
    $.confirm({
        title: 'Editar grupo',
        content: 'url:/admin/tramites/tramitesGrupos/editarGrupo/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/tramitesGrupos/actualizarGrupo',
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
                            obtenerGrupos();
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

$(document).on("click", "#tramites .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#tramites').find('table').remove();
            $('#tramites').find('.text-center').remove();
            $('#tramites').append(data);
        }
    });
});

$(document).on("click", "#grupos .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#grupos').find('table').remove();
            $('#grupos').find('.text-center').remove();
            $('#grupos').append(data);
        }
    });
});

function nuevoTramite() {
    $.confirm({
        title: 'Nuevo tramite',
        content: 'url:/admin/tramites/tramites/nuevoTramite',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/tramites/crearTramite',
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
                            obtenerTramites();
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

function nuevoGrupo() {
    $.confirm({
        title: 'Nuevo grupo',
        content: 'url:/admin/tramites/tramitesGrupos/nuevoGrupo',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/tramitesGrupos/crearGrupo',
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
                            obtenerGrupos();
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

function eliminarTramite(id) {
    $.confirm({
        title: 'Eliminar tramite',
        content: 'Está seguro de eliminar este tramite?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-red',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/tramites/eliminarTramite/' + id,
                        dataType: 'html',
                        method: 'get',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.eliminar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerTramites();
                    }).fail(function () {
                        self.buttons.actualizar.disable();
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

function activarTramite(id) {
    $.confirm({
        title: 'Restaurar tramite',
        content: 'Está seguro de restaurar este tramite?',
        buttons: {
            restaurar: {
                text: 'Restaurar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        url: '/admin/tramites/tramites/restaurarTramite/' + id,
                        dataType: 'html',
                        method: 'get',
                        data: this.$content.find('form').serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.restaurar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerTramites();
                    }).fail(function () {
                        self.buttons.actualizar.disable();
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