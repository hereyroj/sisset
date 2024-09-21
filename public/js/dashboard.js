$(document).ready(function () {
    notifications();
});

function notifications() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/admin/notificaciones/obtenerTodas',
        dataType: 'html',
        success: function (data) {
            $('.notifications').empty().html(data);
        }
    });
}