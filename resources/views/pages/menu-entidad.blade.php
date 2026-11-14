<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $entity->name }} · Menú de entidades IDEAL.10</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if ($entity->logo_url)
        <link rel="icon" href="{{ $entity->logo_url }}">
    @endif
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    {{-- Emerald band behind the hero; the content card overlaps its bottom edge. --}}
    <div class="relative overflow-hidden bg-emerald-700">
        <div class="pointer-events-none absolute -top-24 -right-24 h-80 w-80 rounded-full bg-emerald-500/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-16 h-72 w-72 rounded-full bg-emerald-900/40 blur-3xl"></div>

        <div class="relative mx-auto max-w-5xl px-4 pt-6 pb-36 sm:px-6">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="/assets/img/big_logo_white.png" alt="IDEAL.10" class="h-12 w-auto sm:h-14">
            </a>

            <div class="mt-12 flex flex-col items-center text-center">
                <div class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-emerald-950/30 ring-4 ring-white/20 sm:h-32 sm:w-32">
                    @if ($entity->logo_url)
                        <img src="{{ $entity->logo_url }}" alt="Logo de {{ $entity->name }}" class="h-full w-full object-contain p-4">
                    @else
                        <span class="text-4xl font-extrabold text-emerald-700" aria-hidden="true">{{ $entity->initials }}</span>
                    @endif
                </div>

                <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">{{ $entity->name }}</h1>
                <p class="mt-2 text-base text-emerald-100">Elige la dependencia a la que quieres ingresar.</p>
            </div>
        </div>
    </div>

    <main class="relative mx-auto -mt-24 max-w-5xl px-4 pb-16 sm:px-6">
        @if ($links->isEmpty())
            <div class="rounded-3xl bg-white p-10 text-center shadow-xl shadow-slate-200/60 ring-1 ring-slate-100">
                <p class="font-semibold text-slate-900">Esta entidad todavía no tiene dependencias publicadas.</p>
                <p class="mt-1 text-sm text-slate-500">Vuelve a intentarlo más tarde.</p>
            </div>
        @else
            <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($links as $link)
                    <li>
                        <a
                            href="{{ $link->href }}"
                            target="_blank"
                            rel="noopener"
                            class="group flex h-full items-center gap-4 rounded-2xl bg-white p-4 shadow-lg shadow-slate-200/60 ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-200/50 hover:ring-emerald-300 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-300"
                        >
                            <x-entity-logo :model="$link" class="h-14 w-14 rounded-xl text-lg transition duration-300 group-hover:scale-105" />

                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-base font-bold text-slate-900 transition group-hover:text-emerald-700">{{ $link->name }}</span>
                                <span class="block truncate text-xs text-slate-500">{{ $link->host() }}</span>
                            </span>

                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition duration-300 group-hover:bg-emerald-600 group-hover:text-white">
                                <x-icon.arrow-right class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <p class="mt-12 text-center text-xs text-slate-400">
            Plataforma <a href="{{ route('home') }}" class="font-semibold text-slate-500 hover:text-emerald-700">IDEAL.10</a> · Software para entidades públicas territoriales
        </p>
    </main>
</body>
</html>
