<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'R3doc') }} - API Documentation</title>
    <link rel="stylesheet" href="{{ asset(mix('app.css', 'vendor/r3doc')) }}">
</head>
<body>
    <div id="app" class="h-full bg-gray-200">
        <v-app :routes="{!! json_encode($routes) !!}"></v-app>
    </div>
    <script src="{{ asset(mix('app.js', 'vendor/r3doc')) }}"></script>
</body>
</html>
