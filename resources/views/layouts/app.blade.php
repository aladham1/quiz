<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        html,body {
            height: 100%;
        }
        
    </style>
    @yield('styles')
</head>
<body class="custom-grad">
    @yield('content')
</body>

<!-- jQuery -->
<script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>
<script>
    $( document ).ready(function() {
      $('input').focus( function () {
          $(this).prev('.input-group-prepend').addClass('prepend-shadow');
      });
      $('input').focusout( function () {
          $(this).prev('.input-group-prepend').removeClass('prepend-shadow');
      });
    $('.input-group-prepend').first().addClass('prepend-shadow');
    });
</script>
</html>
