<header class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center gap-4">

        {{-- Logo --}}
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto">
        </a>

        {{-- (opcionális) nyelv/zaszlo --}}
        <div class="flex items-center gap-2">
            <span class="inline-block w-6 h-4 border border-slate-200"></span>
        </div>

        {{-- Kereső (középen) --}}
        <form action="{{ route('catalog.index') }}" method="get" class="flex-1 max-w-xl">
            <div class="relative">
                <input name="q" value="{{ request('q') }}"
                       class="w-full rounded-full border border-slate-300 pl-10 pr-3 py-2 text-sm bg-slate-50"
                       placeholder="Cikkszám/megnevezés (F2)">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">🔎</span>
            </div>
        </form>

        {{-- Menü --}}
        <nav class="hidden lg:flex items-center gap-6 text-sm">
            <a class="hover:underline {{ request()->routeIs('catalog.index') ? 'text-blue-700 font-semibold' : '' }}"
               href="{{ route('catalog.index') }}">Főoldal</a>
            <a class="hover:underline" href="{{ route('page.about') }}">Rólunk</a>
            <a class="hover:underline" href="{{ route('page.career') }}">Karrier</a>
            <a class="hover:underline" href="{{ route('page.contact') }}">Kapcsolat</a>
            <a class="hover:underline" href="{{ route('catalog.index') }}">Webshop</a>
        </nav>

        {{-- Kosár --}}
        <a href="{{ route('cart.show') }}" class="flex items-center gap-2 text-sm hover:underline">
            🛒 <span>Kosár</span>
        </a>

        {{-- Auth blokk --}}
        <div class="flex items-center gap-2 text-sm">
            @auth
                <span class="text-slate-600 hidden sm:inline">Szia, {{ auth()->user()->name }}</span>
                <form method="post" action="{{ route('auth.logout') }}">
                    @csrf
                    <button class="hover:underline text-blue-700">Kijelentkezés</button>
                </form>
            @else
                <a class="hover:underline text-blue-700" href="{{ route('auth.login') }}">Bejelentkezés</a>
                <span class="text-slate-400">/</span>
                <a class="hover:underline text-blue-700" href="{{ route('auth.register') }}">Regisztráció</a>
            @endauth
        </div>
    </div>

    {{-- vékony piros csík unixosan --}}
    <div class="h-1 bg-red-600"></div>
</header>
