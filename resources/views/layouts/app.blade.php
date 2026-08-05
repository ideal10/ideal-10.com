@php($site = \App\Models\SiteSetting::current())

<!DOCTYPE html>
<html lang="{{ $site->lang ?? 'en-US' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - {{ $site->title }}</title>
        <meta name="description" content="IDEAL.10: software ERP en la nube para la gestión administrativa, financiera y normativa de entidades públicas territoriales.">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-white">
        <x-navbar />

        <main class="min-h-screen">
            @yield('content')
        </main>

        <x-footer />

        @stack('scripts')
    </body>
</html>
