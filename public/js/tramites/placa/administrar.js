$(document).ready(function () {
    obtenerPlacas();

    $('.datepicker').pickadate({
        selectYears: true,
        selectMonths: true,
        formatSubmit: 'yyyy-mm-dd',
    });

    var fecha = $('#fecha_inicio').val();
    var $inputFecha = $('#fecha_inicio').pickadate();
    var pickerFecha = $inputFecha.pickadate('picker');
    pickerFecha.set('select', fecha, {
        format: 'yyyy-mm-dd'
    });

    var fecha2 = $('#fecha_fin').val();
    var $inputFecha2 = $('#fecha_fin').pickadate();
    var pickerFecha2 = $inputFecha2.pickadate('picker');
    pickerFecha2.set('select', fecha2, {
        format: 'yyyy-mm-dd'
    });
});

function obtenerPlacas() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/tramites/placas/obtenerPlacas',
        dataType: 'html',
        success: function (data) {
            $('#listadoRangos').empty().html(data);
        }
    });
}

$('#listadoRangos').on("click", ".pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#listadoRangos').empty().html(data);
        }
    });
});

function nuevasPlacas() {
    $.confirm({
        title: 'Nuevos placas',
        content: 'url:/admin/tramites/placas/nuevasPlacas',
        buttons: {
            registrar: {
                text: 'Registrar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/placas/ingresarPlacas',
                            dataType: 'html',
                            method: 'post',
                            data: this.$content.find('form').serialize(),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.registrar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerPlacas();
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

$(document).on('change', '#seleccion', function () {
    if (this.checked) {
        $('#placas table').find('input:checkbox').prop('checked', true);
    } else {
        $('#placas table').find('input:checkbox').prop('checked', false);
    }
});

function editarPlaca(id) {
    $.confirm({
        title: 'Editar placa',
        content: 'url:/admin/tramites/placas/editarPlaca/' + id,
        buttons: {
            actualizar: {
                text: 'Actualizar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/placas/editarPlaca',
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
                            obtenerPlacas();
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

function liberacionPlacas() {
    var values = $("input[name='seleccionados\\[\\]']").map(function () {
        if ($(this).is(':checked')) {
            return $(this).val();
        }
    }).get();
    if (values.length > 0) {
        $.confirm({
            title: 'Liberar placas',
            content: 'Está seguro de querer liberar estas placas?',
            buttons: {
                liberar: {
                    text: 'Liberar',
                    btnClass: 'btn-red',
                    action: function () {
                        var self = this;
                        $.ajax({
                            url: '/admin/tramites/placas/multipleLiberacionPlacas',
                            dataType: 'html',
                            method: 'post',
                            data: {
                                evs: values
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }).done(function (response) {
                            self.buttons.liberar.disable();
                            self.setContent(response);
                            self.setTitle('Terminado');
                            obtenerPlacas();
                        }).fail(function () {
                            self.buttons.liberar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    }
                },
                cancelar: {
                    text: 'Cancelar',
                    action: function () {}
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

                    }
                }
            }
        });
    }
}

function liberarPlaca(id) {
    $.confirm({
        title: 'Liberar placa',
        content: 'url:/admin/tramites/placas/liberarPlaca/' + id,
        buttons: {
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    obtenerPlacas();
                }
            }
        }
    });
}