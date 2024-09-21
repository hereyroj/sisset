$(document).ready(function () {
    obtenerPublicaciones();
    obtenerCategorias();
    obtenerEstados();
});

function obtenerPublicaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/posts/obtenerPublicaciones',
        dataType: 'html',
        success: function (data) {
            $('#publicaciones').empty().html(data);
        }
    });
}

function obtenerCategorias() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/posts/obtenerCategorias',
        dataType: 'html',
        success: function (data) {
            $('#categorias').empty().html(data);
        }
    });
}

function obtenerEstados() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/posts/obtenerEstados',
        dataType: 'html',
        success: function (data) {
            $('#estados').empty().html(data);
        }
    });
}

function nuevaCategoria() {
    $.confirm({
        title: 'Nueva Categoría',
        content: 'url:/admin/posts/nuevaCategoria',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                    $.ajax({
                        url: '/admin/posts/nuevaCategoria',
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
                        self.buttons.crear.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCategorias();
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

function nuevoEstado() {
    $.confirm({
        title: 'Nuevo Estado',
        content: 'url:/admin/posts/nuevoEstado',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                    $.ajax({
                        url: '/admin/posts/nuevoEstado',
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
                        obtenerEstados();
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

function editarCategoria(id) {
    $.confirm({
        title: 'Editar Categoría',
        content: 'url:/admin/posts/editarCategoria/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                    $.ajax({
                        url: '/admin/posts/editarCategoria',
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
                        self.buttons.editar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerCategorias();
                    }).fail(function () {
                        self.buttons.editar.disable();
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

function editarEstado(id) {
    $.confirm({
        title: 'Editar Estado',
        content: 'url:/admin/posts/editarEstado/' + id,
        buttons: {
            editar: {
                text: 'Editar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    var self = this;
                    $.ajax({
                        url: '/admin/posts/editarEstado',
                        dataType: 'html',
                        method: 'post',
                        data: frm.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (response) {
                        self.buttons.editar.disable();
                        self.setContent(response);
                        self.setTitle('Terminado');
                        obtenerEstados();
                    }).fail(function () {
                        self.buttons.editar.disable();
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