<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Warehouse Space</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark default-color fixed-top">
            <div class="container">
                <a class="ws-logo" href="#">
                    <img src="{{ asset('/images/WSLogo.png') }}" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('dashboard') }}">Instructions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('warehouse.setting') }}">Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @if(Session::has('success-message') || Session::has('error-message'))
        <div id="redirect_alert" class="alert @if(Session::has('success-message')) alert-success @elseif(Session::has('error-message')) alert-danger @endif fade in alert-dismissable">
            <a href="javascript:void(0);" onclick="$(this).parent().remove();" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>@if(Session::has('success-message')) Success! @elseif(Session::has('error-message')) Error! @endif </strong>@if(Session::has('success-message')) {{ Session::pull('success-message') }} @elseif(Session::has('error-message')) {{ Session::pull('error-message') }} @endif
        </div>
        @endif
        <div class="main-wrapper-content">
            <h1 class="text-center">Warehouse Space Dashboard</h1>
            <div class="container">
                @yield('content')
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>
