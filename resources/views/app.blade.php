<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nesabox</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
  <link rel="stylesheet" href="{{ asset('css/codemirror.min.css') }}">

  <script>
    window.auth = '{!! $auth !!}'
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    }
    window.github_login_url = '{{ $github_login_url }}'
  </script>
  <script src="{{ mix('js/main.js') }}" defer></script>
  <script src="https://cdn.paddle.com/paddle/paddle.js" defer></script>
  <script>
    // @see https://docs.headwayapp.co/widget for more configuration options.
    var HW_config = {
      selector: "#change-log", // CSS selector where to inject the badge
      account:  "JmeL2J"
    }
  </script>
  <script async src="https://cdn.headwayapp.co/widget.js"></script>
</head>
<body class='bg-page bg-gray-100 font-sans'>
    <div id="app">
      <router-view></router-view>
    </div>
</body>
</html>
