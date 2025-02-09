<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    <div class="hero bg-base-200 min-h-screen">
        <div class="card bg-base-100 w-full max-w-md shrink-0 shadow-2xl">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts
</body>

</html>
