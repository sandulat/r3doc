<html lang="en">
<head>
  <meta charset="utf-8">

  <title>{{ config('app.name', 'R3doc') }}</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" href="css/styles.css?v=1.0">
  <link rel="stylesheet" href="{{ asset(mix('app.css', 'vendor/r3doc')) }}">
</head>

<body>
  <script src="{{ asset(mix('app.js', 'vendor/r3doc')) }}"></script>
</body>
</html>
