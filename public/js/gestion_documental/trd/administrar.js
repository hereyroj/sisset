$(document).ready(function () {
    obtenerSeries();
    obtenerSubSeries();

    //cargar la sub series para la pestaña de tipos
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#dpbSSerie').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $('#dpbSubSerie').empty();
        $.each(data, function (key, val) {
            $('#dpbSubSerie').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
        obtenerTipos();
    })
});

function obtenerSeries() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/trd/obtenerSeries/list',
        }).done(function (data) {
            $('#series').empty().html(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error',
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

function obtenerSubSeries() {
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/admin/trd/obtenerSubSeries/' + $('#dpbSerie').val() + '/list',
        }).done(function (data) {
            $('#subseries').find('table').remove();
            $('#subseries').append(data);
        })
        .fail(function () {
            $.alert({
                title: 'Error',
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

function obtenerTipos() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerTiposDocumentos/' + $('#dpbSubSerie').val() + '/list',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposdocumentos').find('table').remove();
        $('#tiposdocumentos').find('.text-center').remove();
        $('#tiposdocumentos').append(data);
    })
}

function crearSerie() {
    $.confirm({
        title: 'Crear Serie',
        content: 'url:/admin/trd/crearSerie',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/trd/crearSerie',
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
                            obtenerSeries();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la creación de la serie.',
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

function crearSubSerie() {
    $.confirm({
        title: 'Crear Sub-Serie',
        content: 'url:/admin/trd/crearSubSerie',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/trd/crearSubSerie',
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
                            obtenerSubSeries();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la creación de la sub-serie.',
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

function crearTipo() {
    $.confirm({
        title: 'Crear Tipo Documento',
        content: 'url:/admin/trd/crearTipoDocumento',
        buttons: {
            crear: {
                text: 'Crear',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/trd/crearTipoDocumento',
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
                            obtenerTipos();
                        }).fail(function () {
                            self.buttons.crear.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la creación del tipo documento.',
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

$(document).on("click", "#series .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#series').empty().html(data);
        }
    });
});

$(document).on("click", "#subseries .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#sub-series').empty().html(data);
        }
    });
});

$(document).on("click", "#tiposdocumentos .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('#tiposdocumentos').find('table').remove();
            $('#tiposdocumentos').find('.text-center').remove();
            $('#tiposdocumentos').append(data);
        }
    });
});

function eliminarSerie(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/trd/eliminarSerie/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerSeries();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
                    });
                    return false;
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
            },
        }
    });
}

function eliminarSubSerie(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/trd/eliminarSubSerie/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerSubSeries();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
                    });
                    return false;
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
            },
        }
    });
}

function eliminarTipo(id) {
    $.confirm({
        icon: 'glyphicon glyphicon-warning-sign',
        title: 'Está seguro!',
        columnClass: 'medium',
        content: 'Está seguro de querer eliminar este elemento?',
        buttons: {
            eliminar: {
                text: 'Eliminar',
                btnClass: 'btn-primary',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: '/admin/trd/eliminarTipoDocumento/' + id,
                        dataType: 'html',
                    }).done(function (data) {
                        obtenerTipos();
                        self.setContent(data);
                        self.buttons.eliminar.disable();
                    });
                    return false;
                }
            },
            cancel: {
                text: 'Cancelar',
                btnClass: 'btn-danger',
            },
        }
    });
}

$(document).on('change', '#dpbSerie', function () {
    obtenerSubSeries();
});

$(document).on('change', '#dpbSSerie', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerSubSeries/' + $('#dpbSSerie').val() + '/json',
        dataType: 'json',
    }).done(function (data) {
        $('#dpbSubSerie').empty();
        $.each(data, function (key, val) {
            $('#dpbSubSerie').append('<option value=' + data[key].id + '>' + data[key].name + '</option>');
        });
        obtenerTipos();
    })
});

$(document).on('change', '#dpbSubSerie', function () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/trd/obtenerTiposDocumentos/' + $('#dpbSubSerie').val() + '/list',
        dataType: 'html',
    }).done(function (data) {
        $('#tiposdocumentos').find('table').remove();
        $('#tiposdocumentos').find('.text-center').remove();
        $('#tiposdocumentos').append(data);
    })
});

function editarSerie(id) {
    $.confirm({
        title: 'Editar Serie',
        content: 'url:/admin/trd/editarSerie/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/trd/editarSerie',
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
                            obtenerSeries();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la edición de la serie.',
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

function editarSubSerie(id) {
    $.confirm({
        title: 'Editar Sub-Serie',
        content: 'url:/admin/trd/editarSubSerie/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/trd/editarSubSerie',
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
                            obtenerSubSeries();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la edición de la sub-serie.',
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

function editarTipo(id) {
    $.confirm({
        title: 'Editar Tipo Documento',
        content: 'url:/admin/trd/editarTipoDocumento/' + id,
        buttons: {
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-blue',
                action: function () {
                    var frm = this.$content.find('form');
                    if (frm.parsley().validate()) {
                        var self = this;
                        $.ajax({
                            url: '/admin/trd/editarTipoDocumento',
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
                            obtenerTipos();
                        }).fail(function () {
                            self.buttons.guardar.disable();
                            self.setContent('No se ha podido realizar la acción.');
                            self.setTitle('Error con el servidor');
                        });
                        return false;
                    } else {
                        $.alert({
                            title: 'Error',
                            content: 'Error en la edición de la sub-serie.',
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