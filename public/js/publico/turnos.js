var turnos = [];
var audio = new Audio('music/beep.mp3');

$(document).ready(function () {
    obtenerTurnos(null);
});

function obtenerTurnos(data) {
    if (data == null) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: '/servicios/turnos/obtenerTurnosLlamados',
            dataType: 'json',
            success: function (data) {
                if (data != undefined && data != null) {
                    $('#turnos').find('tbody').empty();
                    $(data).each(function (key, val) {
                        $('#turnos').find('tbody').append('<tr><td style="height: 77px;">' + val.turno + '</td><td style="height: 77px;">' + val.ventanilla + '</td></tr>');
                    });
                    if (data.length < 5) {
                        for (var i = 0; i <= 5 - data.length; i++) {
                            $('#turnos').find('tbody').append('<tr><td style="height: 77px;"></td><td style="height: 77px;"></td></tr>');
                        }
                    }
                } else {
                    for (var i = 0; i <= 5; i++) {
                        $('#turnos').find('tbody').append('<tr><td style="height: 77px;"></td><td style="height: 77px;"></td></tr>');
                    }
                }
            }
        });
    } else {
        $('#turnos').find('tbody').find('tr').last().remove();
        $('#turnos').find('tbody').prepend('<tr><td style="height: 77px;">' + data.turno.turno + '</td><td style="height: 77px;">' + data.ventanilla.codigo + '</td></tr>');
    }
}

function llamarTurnos() {
    data = turnos.shift();
    if (data != undefined) {
        audio.play();
        obtenerTurnos(data);
        $.confirm({
            title: 'LLAMADO DE TURNO',
            content: '<p style="font-size:90px; color: black; font-weight:bold;">TURNO: ' + data.turno.turno + '<br>VENTANILLA: ' + data.ventanilla.codigo + '</p>',
            type: 'blue',
            icon: 'glyphicon glyphicon-bell',
            closeIcon: false,
            columnClass: 'col-md-8 col-md-offset-2',
            containerFluid: true, // this will add 'container-fluid' instead of 'container'
            theme: 'modern',
            autoClose: 'close|6000',
            onOpenBefore: function () {
                this.buttons.close.hide();
            },
            buttons: {
                close: function () {
                    audio.pause();
                    audio.currentTime = 0;
                }
            }
        });
    }
}

window.setInterval(function () {
    llamarTurnos();
}, 7000);