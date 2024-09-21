$("#logo_menu").change(function () {
    logo_menu_pdffile = document.getElementById("logo_menu").files[0];
    logo_menu_pdffile_url = URL.createObjectURL(logo_menu_pdffile);
    $('#viewer_logo_menu').attr('src', logo_menu_pdffile_url);
});

$("#logo").change(function () {
    logo_pdffile = document.getElementById("logo").files[0];
    logo_pdffile_url = URL.createObjectURL(logo_pdffile);
    $('#viewer_logo').attr('src', logo_pdffile_url);
});

$("#header").change(function () {
    header_pdffile = document.getElementById("header").files[0];
    header_pdffile_url = URL.createObjectURL(header_pdffile);
    $('#viewer_header').attr('src', header_pdffile_url);
});

$("#firma_director").change(function () {
    firma_director_pdffile = document.getElementById("firma_director").files[0];
    firma_director_pdffile_url = URL.createObjectURL(firma_director_pdffile);
    $('#viewer_firma').attr('src', firma_director_pdffile_url);
});

$("#firma_inspector").change(function () {
    firma_inspector_pdffile = document.getElementById("firma_inspector").files[0];
    firma_inspector_pdffile_url = URL.createObjectURL(firma_inspector_pdffile);
    $('#viewer_firma_inspector').attr('src', firma_inspector_pdffile_url);
});