$(document).ready(function () {
    const FMButton = function (context) {
        const ui = $.summernote.ui;
        const button = ui.button({
            contents: '<i class="note-icon-picture"></i> ',
            tooltip: 'File Manager',
            click: function () {
                window.open('/file-manager/summernote', 'fm', 'width=1400,height=800');
            }
        });
        return button.render();
    };

    jQuery.datetimepicker.setLocale('es');

    $('#fecha_publicacion').datetimepicker({
        format: 'Y-m-d H:i'
    });

    $('#fecha_despublicacion').datetimepicker({
        format: 'Y-m-d H:i'
    });

    $('#post_data').summernote({
        lang: 'es-ES',
        tabsize: 2,
        height: 400,
        toolbar: [
            ['insert', ['emoji']],
            ['tool', ['undo', 'redo', 'codeview']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['fm-button', ['fm']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ],
        popover: {
            table: [
                ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                ['custom', ['tableHeaders']]
            ],
        },
        buttons: {
            fm: FMButton
        }
    });

    $('#etiquetas').tagEditor({
        delimiter: ', ',
        placeholder: 'Escriba etiquetas separandolas por comas o espacios'
    });
});

document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('button-image').addEventListener('click', (event) => {
        event.preventDefault();

        window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
    });
});

// set file link
function fmSetLink($url) {
    document.getElementById('image_label').value = $url;
}

function setData(code) {
    $('#post_data').summernote('code', code);
}