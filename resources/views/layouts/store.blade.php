<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MiMaParts' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
<div class="min-h-screen">
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 py-3 flex items-center gap-3">
            <a href="/" class="flex items-center gap-2">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400"></div>
                <div class="leading-tight">
                    <div class="font-semibold tracking-wide">MiMaParts</div>
                    <div class="text-xs text-slate-400 -mt-0.5">Autoalkatrész webshop</div>
                </div>
            </a>

            <div class="ml-auto flex items-center gap-2">
                <a href="/kereses" class="rounded-xl border border-white/10 px-3 py-2 text-sm hover:border-white/20">Keresés</a>
                <a href="/kosar" class="rounded-xl bg-white/10 px-3 py-2 text-sm hover:bg-white/15">Kosár</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        {{ $slot }}
    </main>

    <footer class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-10 text-sm text-slate-400 flex flex-col md:flex-row gap-6 md:items-center md:justify-between">
            <div>© {{ date('Y') }} MiMaParts — Minden jog fenntartva.</div>
            <div class="flex gap-6">
                <a class="hover:text-white" href="#">ÁSZF</a>
                <a class="hover:text-white" href="#">Adatvédelem</a>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
