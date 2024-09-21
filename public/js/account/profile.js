$('.datepicker').pickadate({
    selectYears: true,
    selectMonths: true,
    formatSubmit: 'yyyy-mm-dd',
});

$('.timepicker').pickatime({
    format: 'HH:i',
    formatSubmit: 'HH:i',
    hiddenSuffix: '_submit'
});

$(document).ready(function () {
    $('.datepicker').each(function (index, elemento) {
        if ($(this).val() !== '') {
            var $input = $(this).pickadate();
            var picker = $input.pickadate('picker');
            picker.set('select', $(this).val(), {format: 'yyyy-mm-dd'});

        }
        picker = '';
    });
    $('.timepicker').each(function (index, elemento) {
        if ($(this).val() !== '') {
            var $input = $(this).pickatime();
            var picker = $input.pickatime('picker');
            picker.set('select', $(this).val(), {format: 'H:i'});

        }
        picker = '';
    });
});

$('#asignados').change(function () {
    var checkboxes = $('.asignados input[type=checkbox]:not([only-read="true"])');
    if ($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
});

$('#inactivos').change(function () {
    var checkboxes = $('.inactivos input[type=checkbox]:not([only-read="true"])');
    if ($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
});

$('#temporales').change(function () {
    var checkboxes = $('.temporales input[type=checkbox]:not([only-read="true"])');
    if ($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
});

function desactivar2fa() {
    $.confirm({
        title: 'Desactivar 2FA',
        content: 'url:/admin/cuenta/desactivar2fa',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            desactivar: {
                text: 'Desactivar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/cuenta/desactivar2fa',
                        data: this.$content.find('form').serialize(),
                        dataType: 'html',
                        success: function (data) {
                            self.buttons.desactivar.disable();
                            self.setContent(data);
                            obtenerSolicitudes();
                        }
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    location.reload(); 
                }
            }
        }
    });
}

function desactivarU2f() {
    $.confirm({
        title: 'Desactivar U2F',
        content: 'url:/admin/cuenta/desactivarU2f',
        columnClass: 'col-md-6 col-md-offset-3',
        buttons: {
            desactivar: {
                text: 'Desactivar',
                btnClass: 'btn-blue',
                action: function () {
                    var self = this;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: '/admin/cuenta/desactivarU2f',
                        data: this.$content.find('form').serialize(),
                        dataType: 'html',
                        success: function (data) {
                            self.buttons.desactivar.disable();
                            self.setContent(data);
                            obtenerSolicitudes();
                        }
                    });
                    return false;
                }
            },
            cerrar: {
                text: 'Cerrar',
                action: function () {
                    location.reload(); 
                }
            }
        }
    });
}
