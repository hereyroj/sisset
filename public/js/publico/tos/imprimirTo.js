$(document).ready(function () {
    var width = $('.logo').width();
    var height = $('.logo').height();
    var verticalWidth = $('.rotate').height();
    var verticalHeight = $('.rotate').width();
    var style = $('<style>.cajaFirma{width: ' + $('.firma').width() + '; height: ' + $('.firma').height() + '; padding: 0; margin: 0} .rotate img {left: 0 !important; margin: 0 !important; width: 16px; height:' + ($('.rotate').height() - 1) + '; } .logo img { overflow:hidden; width: ' + width + '; height:' + height + '; } .textoVertical{ height: ' + verticalHeight + '; position: fixed; top: 168px; left:14px; transform: rotate(-90deg); transform-origin: 10px 10px; z-index: 2; color:#000000; font-size: 6px; } #watermark { position: fixed; top: ' + $('.table').height() * 0.5 + '; width: ' + $('.table').width() + '; text-align: center; transform: rotate(-37deg); -webkit-transform: rotate(-37deg); transform-origin: 50% 50%;  z-index: -1000;  color: #ce8483; font-weight: bold; font-size: 26px;  border-top: 1px solid; border-bottom: 1px solid;}.subtd{ width: 25% !important; max-width: 25% !important; min-width: 25% !important; padding-right: 0 !important; padding-left: 0 !important;}</style>');
    $('html > head').append(style);
    var base_url = "{{asset('img/Untitled-1.png')}}";
    $('.logo').empty().append("<img src='" + base_url + "'>");
    base_url = "{{asset('img/txt_to.jpg')}}";
    $('.rotate').empty().append("<img src='" + base_url + "'>");
})