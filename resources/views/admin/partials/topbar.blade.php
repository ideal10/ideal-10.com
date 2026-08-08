<header class="admin-topbar">
    <button
        type="button"
        data-drawer-target="admin-sidebar"
        data-drawer-toggle="admin-sidebar"
        data-drawer-show="admin-sidebar"
        data-drawer-placement="left"
        aria-controls="admin-sidebar"
        class="btn-icon sm:hidden"
    >
        <span class="sr-only">Abrir menú</span>
        <x-icon.hamburger class="h-5 w-5" />
    </button>

    <div class="min-w-0 flex-1">
        @isset($header)
            {{ $header }}
        @endisset
    </div>

    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 focus:outline-none">
                {{ Auth::user()->name }}
                <x-admin.icon name="chevron-down" class="h-4 w-4" />
            </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-dropdown-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</header>
