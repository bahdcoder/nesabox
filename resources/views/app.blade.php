<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nesabox</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <script src="{{ mix('js/main.js') }}" defer></script>

  <script>
    window.auth = '{!! $auth !!}'
  </script>
</head>
<body class='bg-page bg-gray-100'>
    <div id="app">
      <router-view></router-view>
    </div>
</body>
</html>
