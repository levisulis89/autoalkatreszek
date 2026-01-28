<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MiMaParts – Autóalkatrész Webshop</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased">

<div class="min-h-screen flex flex-col">

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 py-4 flex items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400"></div>
                <div class="leading-tight">
                    <div class="font-semibold tracking-wide text-lg">MiMaParts</div>
                    <div class="text-xs text-slate-400 -mt-0.5">Autoalkatrész webshop</div>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-6 ml-10 text-sm text-slate-300">
                <a href="#" class="hover:text-white">Kategóriák</a>
                <a href="#" class="hover:text-white">Akciók</a>
                <a href="#" class="hover:text-white">Szállítás</a>
                <a href="#" class="hover:text-white">Kapcsolat</a>
            </nav>

            <div class="ml-auto flex items-center gap-3">
                <a href="#" class="rounded-xl border border-white/10 px-4 py-2 text-sm hover:border-white/20">
                    Kosár
                </a>
                <a href="#" class="rounded-xl bg-white/10 px-4 py-2 text-sm hover:bg-white/20">
                    Bejelentkezés
                </a>
            </div>
        </div>
    </header>

    {{-- HERO / SEARCH --}}
    <main class="flex-1">
        <section class="relative overflow-hidden">
            <div class="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl"></div>

            <div class="mx-auto max-w-7xl px-4 py-20">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-5xl font-semibold tracking-tight">
                        Alkatrész keresés<br>
                        <span class="text-slate-300">gyorsan. pontosan. profin.</span>
                    </h1>

                    <p class="mt-4 text-slate-300 text-lg">
                        Keress cikkszám, OEM szám, gyártó vagy jármű alapján.
                        Raktárkészlet, kompatibilitás, korrekt árak.
                    </p>

                    <form class="mt-8">
                        <div class="flex flex-col md:flex-row gap-3">
                            <input
                                type="text"
                                placeholder="Pl.: 03G115105A • Bosch • fékbetét"
                                class="w-full rounded-2xl bg-slate-900/70 border border-white/10 px-5 py-4 text-sm outline-none focus:border-white/30"
                            >
                            <button
                                type="submit"
                                class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-4 text-sm font-semibold text-slate-950 hover:opacity-90"
                            >
                                Keresés
                            </button>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <select class="rounded-2xl bg-slate-900/70 border border-white/10 px-4 py-3 text-sm">
                                <option>Gyártó</option>
                            </select>
                            <select class="rounded-2xl bg-slate-900/70 border border-white/10 px-4 py-3 text-sm">
                                <option>Kategória</option>
                            </select>
                            <select class="rounded-2xl bg-slate-900/70 border border-white/10 px-4 py-3 text-sm">
                                <option>Jármű kiválasztása</option>
                            </select>
                        </div>
                    </form>

                    <div class="mt-6 flex flex-wrap gap-2 text-xs text-slate-300">
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">OEM kompatibilitás</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Valós raktárkészlet</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Gyors szállítás</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Profi támogatás</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURE CARDS --}}
        <section class="mx-auto max-w-7xl px-4 pb-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 hover:bg-white/10 transition">
                    <div class="text-lg font-semibold">Fékalkatrészek</div>
                    <p class="mt-2 text-sm text-slate-300">
                        Tárcsák, betétek, féknyergek – ismert gyártóktól.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 hover:bg-white/10 transition">
                    <div class="text-lg font-semibold">Szűrők & olajok</div>
                    <p class="mt-2 text-sm text-slate-300">
                        Olaj-, levegő-, pollen- és üzemanyagszűrők.
                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 hover:bg-white/10 transition">
                    <div class="text-lg font-semibold">Futómű</div>
                    <p class="mt-2 text-sm text-slate-300">
                        Lengőkarok, gömbfejek, szilentek – biztos tartás.
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-10 flex flex-col md:flex-row gap-6 md:items-center md:justify-between text-sm text-slate-400">
            <div>© {{ date('Y') }} MiMaParts – Minden jog fenntartva.</div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white">ÁSZF</a>
                <a href="#" class="hover:text-white">Adatvédelem</a>
                <a href="#" class="hover:text-white">Kapcsolat</a>
            </div>
        </div>
    </footer>

</div>

</body>
</html>
