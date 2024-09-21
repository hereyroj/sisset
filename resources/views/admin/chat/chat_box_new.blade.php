@extends('layouts.dashboard') 
@section('meta')
<title>Chat</title>
@endsection
 
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
<style>
    html,
    body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    .container-fluid {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%
    }

    .chat_img img {
        max-width: 100%;
        border-radius: 100%;
    }

    .inbox_people {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width: 30%;
        border-right: 1px solid #c4c4c4;
    }

    .inbox_msg {
        clear: both;
        overflow: hidden;
        height: 100%;
    }

    .top_spac {
        margin: 20px 0 0;
    }


    .recent_heading {
        float: left;
        width: 40%;
    }

    .srch_bar {
        display: inline-block;
        text-align: right;
        width: 60%;
    }

    .headind_srch {
        padding: 10px 29px 10px 20px;
        overflow: hidden;
        border-bottom: 1px solid #c4c4c4;
        height: 5%;
    }

    .recent_heading h4 {
        color: #05728f;
        font-size: 21px;
        margin: auto;
    }

    .srch_bar input {
        border: 1px solid #cdcdcd;
        border-width: 0 0 1px 0;
        width: 80%;
        padding: 2px 0 4px 6px;
        background: none;
    }

    .srch_bar .input-group-addon button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        padding: 0;
        color: #707070;
        font-size: 18px;
    }

    .srch_bar .input-group-addon {
        margin: 0 0 0 -27px;
    }

    .chat_ib h5 {
        font-size: 15px;
        color: #464646;
        margin: 0 0 8px 0;
    }

    .chat_ib h5 span {
        font-size: 13px;
        float: right;
    }

    .chat_ib p {
        font-size: 14px;
        color: #989898;
        margin: auto
    }

    .chat_img {
        float: left;
        width: 11%;
    }

    .chat_ib {
        float: left;
        padding: 0 0 0 15px;
        width: 88%;
    }

    .chat_people {
        overflow: hidden;
        clear: both;
    }

    .chat_list {
        border-bottom: 1px solid #c4c4c4;
        margin: 0;
        padding: 18px 16px 10px;
    }

    .chat_list:hover {
        cursor: pointer;
        background: #ebebeb;
    }

    .inbox_chat {
        height: 95%;
        overflow-y: auto;
    }

    .active_chat {
        background: #ebebeb;
    }

    .incoming_msg_img {
        display: inline-block;
        width: 6%;
    }

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }

    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }

    .received_withd_msg {
        width: 57%;
    }

    .mesgs {
        float: left;
        padding: 30px 15px 0 25px;
        width: 70%;
        height: 100%;
    }

    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0;
        color: #fff;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .outgoing_msg,
    .incoming_msg {
        margin: 0 0 26px 0;
    }

    .outgoing_msg {
        overflow: hidden;
    }

    .sent_msg {
        float: right;
        width: 46%;
    }

    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
    }

    .type_msg {
        position: relative;
        height: 7%;
        padding-top: 10px;
    }

    .msg_send_btn {
        background: #05728f none repeat scroll 0 0;
        border: medium none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 17px;
        height: 100%;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
    }

    .messaging {}

    .msg_history {
        height: 93%;
        overflow-y: auto;
    }

    .write_msg {
        width: 100%;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border: 1px solid #c4c4c4;
        height: 100%;
        padding: 1px 5px;
    }

    .input_msg_write {
        width: 95%;
        height: 100%;
        display: block;
        float: left;
        padding-bottom: 5px;
    }

    .button_msg_write {
        width: 5%;
        height: 100%;
        display: block;
        float: left;
        padding: 5px;
    }
</style>
@endsection
 
@section('content')
<div class="container-fluid">
    <div class="messaging">
        <div class="inbox_msg">
            <div class="inbox_people">
                <div class="headind_srch">
                    <div class="recent_heading">
                        <h4>Historial</h4>
                    </div>
                    <div class="srch_bar">
                        <div class="stylish-input-group">
                            <input type="text" class="search-bar" placeholder="Buscar">
                            <span class="input-group-addon">
                                    <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="inbox_chat">

                </div>
            </div>
            <div class="mesgs">
                <div class="msg_history">

                </div>
                <div class="type_msg">
                    <div class="input_msg_write">
                        <form id="sendMsg">
                            <input type="hidden" id="id" name="id">
                            <input type="hidden" id="origen" name="origen">
                            <textarea class="write_msg" placeholder="Escribe un mensaje" rows="1" style="resize: none;" name="mensaje"></textarea>
                        </form>
                    </div>
                    <div class="button_msg_write">
                        <button class="msg_send_btn" type="button" title="Enviar mensaje" onclick="enviarMensaje()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
 
@section('scripts')
<script type="text/javascript" src="{{asset('js/chat/chat_box_new.js')}}"></script>
<script type="ecmascript" src="{{asset('js/chat/es_chat_box_new.js')}}"></script>
@endsection