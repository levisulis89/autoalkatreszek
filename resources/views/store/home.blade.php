<x-store-layout :title="'MiMaParts – keresés'">
    <section class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-b from-white/5 to-transparent p-8 md:p-12">
        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl"></div>

        <div class="max-w-2xl">
            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight">
                Alkatrész keresés <span class="text-slate-300">cikkszámra, OEM-re, névre</span>
            </h1>
            <p class="mt-3 text-slate-300">
                Profi szűrők, jármű kompatibilitás, raktárkészlet – UNIX-stílusban, de modernül.
            </p>

            <form action="/kereses" method="GET" class="mt-6">
                <div class="flex flex-col md:flex-row gap-3">
                    <input name="q"
                           placeholder="Pl.: 03G115105A, Bosch 0 986..., fékbetét"
                           class="w-full rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm outline-none focus:border-white/20"
                    />
                    <button class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:opacity-95">
                        Keresés
                    </button>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select class="rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm">
                        <option>Gyártó (márka)</option>
                    </select>
                    <select class="rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm">
                        <option>Kategória</option>
                    </select>
                    <select class="rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm">
                        <option>Jármű (opcionális)</option>
                    </select>
                </div>
            </form>

            <div class="mt-6 flex flex-wrap gap-2 text-xs text-slate-300">
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Gyors találatok</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">OEM kompatibilitás</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Raktárkészlet</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Akciók</span>
            </div>
        </div>
    </section>

    <section class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach([['Fék','Top fékalkatrészek'],['Olaj & szűrők','Szűrők, kenőanyagok'],['Futómű','Lengőkarok, gömbfejek']] as $c)
            <a href="#" class="group rounded-3xl border border-white/10 bg-white/5 p-6 hover:bg-white/7 transition">
                <div class="text-lg font-semibold">{{ $c[0] }}</div>
                <div class="mt-1 text-sm text-slate-300">{{ $c[1] }}</div>
                <div class="mt-6 text-sm text-cyan-300 group-hover:text-cyan-200">Megnézem →</div>
            </a>
        @endforeach
    </section>
</x-store-layout>
