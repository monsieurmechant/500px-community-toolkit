<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '500px Community Toolkit') }}</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name', '500px Community Toolkit') }}">
    <meta property="og:description" content="">
    <meta property="og:site_name" content="{{ config('app.name', '500px Community Toolkit') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <script>
      window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div class="container">
    <nav class="nav">
        <div class="nav-left">
            <a class="nav-item is-brand" href="http://bulma.io">
                <span class="icon">
                    <i class="fa fa-wrench"></i>
                </span>
                {{ config('app.name', '500px Community Toolkit') }}
            </a>
        </div>

        <div id="nav-menu" class="nav-right nav-menu">
            <a class="nav-item " href="#">
                My Community
            </a>

            <span class="nav-item">
            <a id="500px" class="button" target="_blank"
               href="http://500px.com/monsieurmechant">
              <span class="icon">
                <i class="fa fa-500px"></i>
              </span>
              <span>Follow Me</span>
            </a>
          </span>

        </div>
    </nav>

</div>
<div id="app">
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
