@extends('layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Radicar PQR - {{Setting::get('empresa_sigla')}}</title>
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <div class="panel-title">Normativa PQR</div>
            </div>
           <div class="card-body">

            </div>
        </div>
    </div>
@endsection