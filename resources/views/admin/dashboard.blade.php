@extends('layouts.dashboard')

@section('meta')
    <title>Escritorio</title>
@endsection

@section('content')
    <div class="container-fluid">
        @if (Session::has('errores'))
            <div class="alert alert-danger">
                <h4>No se ha podido realizar la operación debido a los siguiente inconvenientes:</h4>
                <ul>
                    @foreach (Session::get('errores') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row" style="height: 400px;">
            <div class="col-md-3" id="notificationes" style="height: 400px;">
                <div class="box">
                    <div class="title">
                        <h4>Notificaciones</h4>
                        <hr class="hr">
                    </div>
                    <div class="notifications"></div>
                </div>
            </div>
            <div class="col-md-6" style="height: 400px;"></div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/dashboard.js')}}"></script>
@endsection