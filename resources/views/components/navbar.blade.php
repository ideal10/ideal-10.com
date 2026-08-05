@php($navItems = \App\Models\NavItem::ordered()->get())

<nav class="fixed start-0 top-0 z-20 w-full border-b border-emerald-100 bg-white/75 shadow-sm backdrop-blur-sm dark:border-slate-800 dark:bg-slate-950/90">
    <div class="container-page flex flex-wrap items-center justify-between py-3">
        <a href="/" class="flex items-center" aria-label="Inicio">
            {{-- Backgrounds are set via inline style (not a Tailwind bg-[url(...)] utility) so Vite's
                 CSS pipeline never fingerprints these paths — they must stay exactly /assets/img/*. --}}
            <div class="h-11 w-[145px] bg-contain bg-no-repeat bg-left dark:hidden" style="background-image:url('/assets/img/big_logo_green.png')"></div>
            <div class="hidden h-11 w-[145px] bg-contain bg-no-repeat bg-left dark:block" style="background-image:url('/assets/img/big_logo_white.png')"></div>
        </a>
        <div class="flex items-center space-x-3 rtl:space-x-reverse md:order-2 md:space-x-0">
            <a href="/contacto" class="btn-compact-primary">Contáctanos</a>
            <button data-collapse-toggle="navbar-sticky" type="button" class="ml-3 inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-slate-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:text-slate-300 dark:hover:bg-slate-800 dark:focus:ring-slate-600 md:hidden" aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Abrir menú principal</span>
                <x-icon.hamburger />
            </button>
        </div>
        <div class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-sticky">
            <ul class="mt-4 flex flex-col rounded-xl border border-emerald-100 bg-white p-3 font-medium shadow-sm rtl:space-x-reverse dark:border-slate-800 dark:bg-slate-900 md:mt-0 md:flex-row md:space-x-1 md:border-0 md:bg-transparent md:p-0 md:shadow-none md:dark:bg-transparent">
                @foreach ($navItems as $item)
                    @include('partials.nav-link', ['item' => $item])
                @endforeach
            </ul>
        </div>
    </div>
</nav>
