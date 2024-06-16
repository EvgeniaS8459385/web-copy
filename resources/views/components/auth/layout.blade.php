<!DOCTYPE html>
<html class="h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ Vite::asset("resources/css/app.scss")  }}">
</head>
<body {{$attributes->class(['d-flex flex-column h-100'])}} style="min-width: 800px">
    {{ $slot }}

    <script type="application/javascript" src="{{ Vite::asset("resources/js/app.js") }}"></script>
</body>
</html>
