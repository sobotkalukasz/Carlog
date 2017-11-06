<!doctype html>
<html lang ="pl">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Carlog</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontello.css') }}"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

    @yield('head')

  </head>
  <body>
    <div class="container">

        @include('includes.logo')

        @include('includes.nav')

        @yield('body')

        @include('includes.footer')
  </div>

        @include('includes.stickyjs')

        @yield('script')

  </body>
</html>
