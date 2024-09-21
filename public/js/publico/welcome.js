$(document).ready(function () {
    obtenerUltimasPublicaciones();
    obtenerNotificacionesAviso();
    obtenerNormativas();
});

(function ($) {
    "use strict";
    // Auto-scroll
    $('#sliderUltimasPublicaciones').carousel({
        interval: 5000
    });

    // Control buttons
    $('.next').click(function () {
        $('.carousel').carousel('next');
        return false;
    });
    $('.prev').click(function () {
        $('.carousel').carousel('prev');
        return false;
    });

    // On carousel scroll
    $("#sliderUltimasPublicaciones").on("slide.bs.carousel", function (e) {
        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 6;
        var totalItems = $(".carousel-item").length;
        if (idx >= totalItems - (itemsPerSlide - 1)) {
            var it = itemsPerSlide -
                (totalItems - idx);
            for (var i = 0; i < it; i++) {
                // append slides to end 
                if (e.direction == "left") {
                    $(
                        ".carousel-item").eq(i).appendTo(".carousel-inner");
                } else {
                    $(".carousel-item").eq(0).appendTo(".carousel-inner");
                }
            }
        }
    });
})(jQuery);

function obtenerNormativas() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/normativas/normativas',
        success: function (data) {
            $('.listado-normativas').find('.body-box').append(data);
        }
    });
}

$(document).on("click", ".listado-normativas .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('.listado-normativas').find('.body-box').empty().html(data);
        }
    });
});

function obtenerNotificacionesAviso() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/servicios/notificacionesAviso/notificacionesAviso',
        success: function (data) {
            $('.listado-notificaciones-aviso').find('.body-box').append(data);
        }
    });
}

$(document).on("click", ".listado-notificaciones-aviso .pagination li a", function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: url,
        success: function (data) {
            $('.listado-notificaciones-aviso').find('.body-box').empty().html(data);
        }
    });
});

function obtenerUltimasPublicaciones() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        url: '/posts/ultimasPublicaciones',
        success: function (data) {
            $('#ultimasPublicaciones').empty().html(data);
        }
    });
}