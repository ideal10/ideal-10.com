@extends('layouts.app')

@section('title', 'Enlaces de Interés')

@section('content')
<div class="split-layout">

    {{-- ═══════════════════════════
         IZQUIERDA — hero + buscador
    ═══════════════════════════ --}}
    <aside class="split-sidebar">
        <p class="s-kicker">Directorio operativo</p>
        <h1>Enlaces de interés para gestión pública territorial.</h1>
        <p class="s-lead">Accesos frecuentes a plataformas, entidades de control, consultas normativas y matrices de apoyo administrativo y financiero.</p>

        <div class="split-stats">
            <div class="split-stat">
                <div class="split-stat-num">{{ $portales->count() }}</div>
                <div>
                    <div class="split-stat-label">Portales externos</div>
                    <div class="split-stat-sub">CHIP · SIA · CGN · SGR · ADRES y más</div>
                </div>
            </div>
            <div class="split-stat">
                <div class="split-stat-num">{{ $archivos->count() }}</div>
                <div>
                    <div class="split-stat-label">Matrices descargables</div>
                    <div class="split-stat-sub">Excel · Consulta territorial</div>
                </div>
            </div>
        </div>

        <div class="split-search">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" id="splitSearch" placeholder="Buscar enlace…">
        </div>
        <div class="s-count" id="splitCount"></div>

        <div class="split-footer">
            Cada portal abre en una pestaña nueva.<br>
            Las matrices se descargan directamente.
        </div>
    </aside>

    {{-- ═══════════════════════════
         DERECHA — tarjetas (scroll)
    ═══════════════════════════ --}}
    <main class="split-main">

        <div class="split-group-heading">
            Portales externos
            <span class="split-group-count" id="countPortales">{{ $portales->count() }}</span>
        </div>

        <div class="split-grid" id="gridPortales">
            @foreach ($portales as $enlace)
                <a href="{{ $enlace->url }}" target="_blank" rel="noopener"
                   class="split-card"
                   data-title="{{ mb_strtolower($enlace->title) }}">
                    <div class="split-card-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                        </svg>
                    </div>
                    <div class="split-card-body">
                        <div class="split-card-title">{{ $enlace->title }}</div>
                        <div class="split-card-meta">{{ $enlace->domain() }}</div>
                    </div>
                </a>
            @endforeach

            <div class="split-empty" id="emptyPortales">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <p>Sin resultados.</p>
            </div>
        </div>

        @if ($archivos->count())
        <div class="split-group-heading" id="headingArchivos">
            Matrices descargables
            <span class="split-group-count">{{ $archivos->count() }}</span>
        </div>

        <div class="split-grid" id="gridArchivos">
            @foreach ($archivos as $enlace)
                <a href="{{ $enlace->url }}"
                   class="split-card is-file"
                   data-title="{{ mb_strtolower($enlace->title) }}">
                    <div class="split-card-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                    </div>
                    <div class="split-card-body">
                        <div class="split-card-title">{{ $enlace->title }}</div>
                        <div class="split-card-meta">Archivo descargable</div>
                    </div>
                </a>
            @endforeach
        </div>
        @endif

    </main>
</div>
@endsection

@push('scripts')
    @vite('resources/js/search-interest-links.js')
@endpush
