<header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="h-20 flex items-center gap-4">

            {{-- BAL: Logo --}}
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-3 shrink-0">
                <div class="h-12 w-12 rounded-xl bg-slate-900 flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto drop-shadow">
                </div>
                <div class="hidden sm:block leading-tight">
                    <div class="text-lg font-extrabold tracking-tight text-slate-900">MiMaParts</div>
                    <div class="text-xs text-slate-500 -mt-0.5">Autóalkatrész webshop</div>
                </div>
            </a>

            {{-- KÖZÉP: Kereső --}}
            <div class="flex-1 flex justify-center">
                <form action="{{ route('catalog.index') }}" method="get" class="w-full max-w-2xl">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔎</span>
                        <input
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full rounded-full border border-slate-300 bg-white pl-11 pr-28 py-3 text-sm shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:border-red-400"
                            placeholder="Cikkszám / megnevezés (F2)"
                        >
                        <button
                            type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full px-4 py-2 text-sm font-semibold
                                   bg-slate-900 text-white hover:bg-slate-800"
                        >
                            Keresés
                        </button>
                    </div>

                    <div class="mt-1 text-xs text-slate-500 hidden md:block">
                        Tipp: írj cikkszámot vagy kulcsszót, pl. “fékbetét”, “olajszűrő”
                    </div>
                </form>
            </div>

            {{-- JOBB: Menü + Kosár + Auth --}}
            <div class="flex items-center gap-4 shrink-0">

                <nav class="hidden xl:flex items-center gap-6 text-sm">
                    <a class="hover:text-slate-900 text-slate-700 {{ request()->routeIs('catalog.index') ? 'font-semibold text-red-700' : '' }}"
                       href="{{ route('catalog.index') }}">Főoldal</a>
                    <a class="hover:text-slate-900 text-slate-700" href="{{ route('page.about') }}">Rólunk</a>
                    <a class="hover:text-slate-900 text-slate-700" href="{{ route('page.career') }}">Karrier</a>
                    <a class="hover:text-slate-900 text-slate-700" href="{{ route('page.contact') }}">Kapcsolat</a>
                    <a class="hover:text-slate-900 text-slate-700" href="{{ route('catalog.index') }}">Webshop</a>
                </nav>

                <a href="{{ route('cart.show') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold
                          hover:bg-slate-50 shadow-sm">
                    <span class="text-base">🛒</span>
                    <span>Kosár</span>
                </a>

                <div class="hidden sm:flex items-center gap-2 text-sm">
                    @auth
                        <span class="text-slate-600 hidden md:inline">
                            Szia, <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>
                        </span>
                        <form method="post" action="{{ route('auth.logout') }}">
                            @csrf
                            <button class="rounded-full px-4 py-2 font-semibold text-red-700 hover:bg-red-50">
                                Kijelentkezés
                            </button>
                        </form>
                    @else
                        <a class="rounded-full px-4 py-2 font-semibold text-slate-900 hover:bg-slate-100"
                           href="{{ route('auth.login') }}">Bejelentkezés</a>

                        <a class="rounded-full px-4 py-2 font-semibold bg-red-600 text-white hover:bg-red-700 shadow-sm"
                           href="{{ route('auth.register') }}">Regisztráció</a>
                    @endauth
                </div>

                <div class="sm:hidden flex items-center gap-2">
                    <a href="{{ route('auth.login') }}" class="rounded-full border border-slate-300 px-3 py-2 text-sm">👤</a>
                    <a href="{{ route('cart.show') }}" class="rounded-full border border-slate-300 px-3 py-2 text-sm">🛒</a>
                </div>

            </div>
        </div>
    </div>

    <div class="h-1 bg-gradient-to-r from-red-600 via-red-500 to-red-600"></div>
</header>