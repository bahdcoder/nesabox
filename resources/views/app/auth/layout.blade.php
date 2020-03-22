<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nesabox</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
</head>
<body>
    <div id="app">
      @yield('content')
    </div>

    <script scr="{{ asset('js/main.js') }}"></script>
</body>
</html>
