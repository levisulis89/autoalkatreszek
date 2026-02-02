<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','MiMaParts')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900">

<div class="min-h-screen flex">

    {{-- BAL OLDALI UNIX MENÜ --}}
    <aside class="w-72 bg-blue-900 text-white flex flex-col">
        {{-- LOGO / BRAND --}}
        <div class="h-16 flex items-center gap-3 px-4 border-b border-white/10">
            <img src="{{ asset('images/logo.svg') }}"
                 onerror="this.style.display='none'"
                 class="h-9 w-9"
                 alt="Logo">
            <div class="leading-tight">
                <div class="font-extrabold tracking-wide">MiMaParts</div>
                <div class="text-xs text-white/70">WebShop</div>
            </div>
        </div>

        {{-- KERESŐ A MENÜBEN (opcionális, UNIX feeling) --}}
        <div class="px-4 py-3 border-b border-white/10">
            <div class="text-xs text-white/70 mb-2">Keresés a menüben</div>
            <input type="text"
                   placeholder="Keresés…"
                   class="w-full rounded-md bg-white/10 border border-white/10 px-3 py-2 text-sm placeholder:text-white/50 outline-none focus:bg-white/15">
        </div>

        {{-- MENÜPONTOK --}}
        <nav class="flex-1 px-2 py-3 space-y-1">
            @php
                $is = fn($name) => request()->routeIs($name);
                $item = function($route, $label, $active) {
                    return $active
                        ? "block rounded-md px-3 py-2 text-sm font-semibold bg-white/15"
                        : "block rounded-md px-3 py-2 text-sm text-white/85 hover:bg-white/10";
                };
            @endphp

            <a href="{{ route('catalog.index') }}"
               class="{{ $item('catalog.index','Katalógus', $is('catalog.index')) }}">
                Katalógus
            </a>

            {{-- ha van kosár route-od --}}
            @if(\Illuminate\Support\Facades\Route::has('cart.index'))
                <a href="{{ route('cart.index') }}"
                   class="{{ $item('cart.index','Kosár', $is('cart.index')) }}">
                    Kosár
                </a>
            @endif

            {{-- példa: admin/dashboard --}}
            @if(\Illuminate\Support\Facades\Route::has('filament.admin.pages.dashboard'))
                <a href="{{ route('filament.admin.pages.dashboard') }}"
                   class="block rounded-md px-3 py-2 text-sm text-white/85 hover:bg-white/10">
                    Admin / Dashboard
                </a>
            @endif

            {{-- statikus oldalak ha léteznek --}}
            @if(\Illuminate\Support\Facades\Route::has('page.about'))
                <a href="{{ route('page.about') }}"
                   class="block rounded-md px-3 py-2 text-sm text-white/85 hover:bg-white/10">
                    Rólunk
                </a>
            @endif

            @if(\Illuminate\Support\Facades\Route::has('page.contact'))
                <a href="{{ route('page.contact') }}"
                   class="block rounded-md px-3 py-2 text-sm text-white/85 hover:bg-white/10">
                    Kapcsolat
                </a>
            @endif
        </nav>

        {{-- LÁBLÉC A MENÜBEN --}}
        <div class="px-4 py-3 border-t border-white/10 text-xs text-white/60">
            v1.0 • {{ now()->format('Y') }}
        </div>
    </aside>

    {{-- JOBB OLDALI TARTALOM --}}
    <div class="flex-1 min-w-0">

        {{-- FELSŐ SÁV (ha kell, unix feeling) --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4">
            <div class="font-semibold text-slate-800">@yield('title','MiMaParts')</div>

            <div class="flex items-center gap-3">
                {{-- ide jöhet kosár, login, stb. --}}
                @auth
                    <span class="text-sm text-slate-600">{{ auth()->user()->name ?? 'Belépve' }}</span>
                @endauth
            </div>
        </header>

        <main class="p-4">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>