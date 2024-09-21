@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Liquidar comparendos - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('content')
    <iframe src="http://186.147.8.79:8045/LiquidacionComparendos.aspx" width="100%" style="border:none;min-height: 500px;"></iframe>
@endsection