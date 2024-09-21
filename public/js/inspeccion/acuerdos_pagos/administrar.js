$(document).ready(function () {
    obtenerAcuerdosPagos();
});

function obtenerAcuerdosPagos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/inspeccion/AcuerdosPagos/obtenerAcuerdosPagos',
        dataType: 'html',
        success: function (data) {
            $('#listadoAcuerdosPagos').empty().html(data);
        }
    });
}

$('#listadoAcuerdosPagos').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#listadoAcuerdosPagos').empty().html(data);
        }
    });
});

function nuevoAcuerdoPago() {
    $.confirm({
        title: 'Nuevo AcuerdoPago',
        content: 'url:/admin/inspeccion/AcuerdosPagos/nuevo',
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
                            url: '/admin/inspeccion/AcuerdosPagos/nuevo',
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
                            obtenerAcuerdosPagos();
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

function editarAcuerdoPago(id) {
    $.confirm({
        title: 'Editar AcuerdoPago',
        content: 'url:/admin/inspeccion/AcuerdosPagos/editar/' + id,
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
                            url: '/admin/inspeccion/AcuerdosPagos/editar',
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
                            obtenerAcuerdosPagos();
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

var listadoCuotas = null;

function verCuotas(id) {
    listadoCuotas = $.confirm({
        title: 'Cuotas del acuerdo de pago',
        content: 'url:/admin/inspeccion/AcuerdosPagos/obtenerCuotasAcuerdoPago/' + id,
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

function verDeudor(id) {
    $.confirm({
        title: 'Información del deudor',
        content: 'url:/admin/inspeccion/AcuerdosPagos/verDeudor/' + id,
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {}
            }
        }
    });
}

function filtrarAcuerdosPagos() {
    if ($('#filtrarAcuerdos').val() == undefined) {
        $.alert({
            title: 'Error!',
            content: 'No se ha especificado el valor de búsqueda.',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    action: function () {
                        $('#filtrarAcuerdos').focus();
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
            url: '/admin/inspeccion/AcuerdosPagos/filtrar/' + $('#filtrarAcuerdos').val() + '/' + $('#filtroAcuerdos').val(),
        }).done(function (data) {
            if (data != null && data != undefined && data != '') {
                $('#listadoAcuerdosPagos').empty().html(data);
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