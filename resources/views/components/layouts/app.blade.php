<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <script src="https://kit.fontawesome.com/a51f251d24.js" crossorigin="anonymous"></script>

    @vite('resources/css/app.css')
</head>

<body>
    <x-website.header />
    {{ $slot }}
    <x-website.footer />
    <x-website.toaster />
</body>

</html>
