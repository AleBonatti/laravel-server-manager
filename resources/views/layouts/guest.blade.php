<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-pic bg-cover">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-black/70">
        <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img src="/assets/loghi/logo.svg" class="h-10 m-auto">
            </div>
                
            <div class="sm:mx-auto sm:w-full sm:max-w-md my-8">
                {{ $slot }}
            </div>

            <p class="text-xs text-gray-500 text-center mt-4">Seppia server manager - &copy; 2023 Methalb web design</p>
        </div>
    </body>
</html>
