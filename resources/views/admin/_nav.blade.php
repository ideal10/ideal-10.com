<div class="mb-6 flex flex-wrap gap-4 text-sm">
    <a href="{{ route('admin.site-settings.edit') }}" class="{{ request()->routeIs('admin.site-settings.*') ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-800' }}">Configuración</a>
    <a href="{{ route('admin.nav-items.index') }}" class="{{ request()->routeIs('admin.nav-items.*') ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-800' }}">Navegación</a>
    <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-800' }}">Servicios</a>
    <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-800' }}">Clientes</a>
    <a href="{{ route('admin.entities.index') }}" class="{{ request()->routeIs('admin.entities.*') ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-800' }}">Entidades</a>
    <a href="{{ route('admin.componentes.index') }}" class="{{ request()->routeIs('admin.componentes.*') ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-800' }}">Componentes</a>
</div>
