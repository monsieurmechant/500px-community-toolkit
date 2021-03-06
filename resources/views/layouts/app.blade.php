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
            'user' => Auth::user() ?? null,
            'pusherKey' => config('broadcasting.connections.pusher.key') ?? null,
        ]) !!};
    </script>
</head>
<body>
<div id="app">
</div>


<!-- Scripts -->
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
