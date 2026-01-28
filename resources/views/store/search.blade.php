<x-store-layout title="Keresés – MiMaParts">
    <div class="flex flex-col gap-6">

        {{-- Top search bar --}}
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <form method="GET" action="/kereses" class="flex flex-col md:flex-row gap-3">
                <input
                    name="q"
                    value="{{ $q ?? '' }}"
                    placeholder="Cikkszám / OEM / név..."
                    class="w-full rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm outline-none focus:border-white/20"
                />

                {{-- Keep filters when searching --}}
                <input type="hidden" name="brand_id" value="{{ $filters['brand_id'] ?? '' }}">
                <input type="hidden" name="category_id" value="{{ $filters['category_id'] ?? '' }}">
                <input type="hidden" name="vehicle_id" value="{{ $filters['vehicle_id'] ?? '' }}">
                @if(!empty($filters['in_stock']))
                    <input type="hidden" name="in_stock" value="1">
                @endif

                <button class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:opacity-95">
                    Keresés
                </button>

                <a href="/kereses" class="rounded-2xl bg-white/10 px-5 py-3 text-sm hover:bg-white/15 text-center">
                    Reset
                </a>
            </form>

            @if(empty($q) && empty($filters['brand_id']) && empty($filters['category_id']) && empty($filters['vehicle_id']) && empty($filters['in_stock']))
                <div class="mt-3 text-xs text-slate-400">
                    Tipp: keress <span class="text-slate-200">cikkszám</span>, <span class="text-slate-200">OEM</span> vagy <span class="text-slate-200">név</span> alapján.
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Filters --}}
            <aside class="lg:col-span-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <div class="font-semibold">Szűrők</div>

                    <form method="GET" action="/kereses" class="mt-4 space-y-4">
                        <input type="hidden" name="q" value="{{ $q ?? '' }}"/>

                        <div>
                            <label class="text-xs text-slate-300">Márka</label>
                            <select name="brand_id" class="mt-1 w-full rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm">
                                <option value="">Összes</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" @selected(($filters['brand_id'] ?? null) == $b->id)>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs text-slate-300">Kategória</label>
                            <select name="category_id" class="mt-1 w-full rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm">
                                <option value="">Összes</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(($filters['category_id'] ?? null) == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs text-slate-300">Jármű kompatibilitás</label>
                            <select name="vehicle_id" class="mt-1 w-full rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-3 text-sm">
                                <option value="">Mindegy</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" @selected(($filters['vehicle_id'] ?? null) == $v->id)>
                                        {{ $v->make }} {{ $v->model }} {{ $v->engine }} ({{ $v->year_from }}-{{ $v->year_to }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1 text-xs text-slate-400">MVP: listából. Kövi: cascading.</div>
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-200">
                            <input
                                type="checkbox"
                                name="in_stock"
                                value="1"
                                @checked(!empty($filters['in_stock']))
                                class="rounded border-white/20 bg-slate-900/60"
                            />
                            Csak raktáron
                        </label>

                        <div class="flex gap-2">
                            <button class="flex-1 rounded-2xl bg-white/10 px-4 py-3 text-sm hover:bg-white/15">
                                Szűrés
                            </button>
                            <a href="/kereses{{ !empty($q) ? ('?q=' . urlencode($q)) : '' }}" class="flex-1 rounded-2xl bg-white/5 px-4 py-3 text-sm hover:bg-white/10 text-center">
                                Szűrők törlése
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- Results --}}
            <section class="lg:col-span-9">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm text-slate-300">
                        Találatok: <span class="text-white font-semibold">{{ $total ?? $products->count() }}</span>

                    </div>
                </div>

                @if(empty($products) || $products->isEmpty())
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-10 text-slate-300">
                        @if(!empty($q))
                            Nincs találat a(z) <span class="text-white font-semibold">"{{ $q }}"</span> keresésre.
                        @else
                            Add meg a keresési kifejezést vagy válassz szűrőt.
                        @endif
                        <div class="mt-2 text-sm text-slate-400">
                            Tipp: próbáld cikkszámra (SKU), OEM számra vagy rövidebb kulcsszóra.
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($products as $p)
                            @php
                                $av = method_exists($p,'availability') ? $p->availability() : ['qty'=>null,'lead_days'=>null];
                                $price = $p->prices->sortByDesc('valid_from')->first();
                            @endphp

                            <div class="group rounded-3xl border border-white/10 bg-white/5 p-5 hover:bg-white/7 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs text-slate-400">{{ $p->brand?->name ?? '—' }}</div>
                                        <div class="font-semibold leading-snug break-words">{{ $p->name }}</div>
                                        <div class="mt-1 text-xs text-slate-400">
                                            SKU: <span class="text-slate-200">{{ $p->sku }}</span>
                                        </div>
                                        @if(!empty($p->oem_number))
                                            <div class="text-xs text-slate-400">
                                                OEM: <span class="text-slate-200">{{ $p->oem_number }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <span class="shrink-0 rounded-xl border border-white/10 bg-slate-950/40 px-2.5 py-1 text-xs text-slate-200">
                                        {{ $p->category?->name ?? 'Kategória' }}
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <div class="text-lg font-semibold whitespace-nowrap">
                                        {{ $price ? number_format($price->gross, 0, '', ' ') . ' Ft' : 'Ár egyeztetés' }}
                                    </div>

                                    @if($av['qty'] !== null)
                                        @if($av['qty'] > 0)
                                            <span class="whitespace-nowrap rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/20 px-3 py-1 text-xs">
                                                Raktáron: {{ $av['qty'] }} db • {{ $av['lead_days'] }} nap
                                            </span>
                                        @else
                                            <span class="whitespace-nowrap rounded-full bg-amber-500/15 text-amber-300 border border-amber-500/20 px-3 py-1 text-xs">
                                                Nincs készleten • ~{{ $av['lead_days'] }} nap
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <button type="button" class="mt-4 w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-4 py-3 text-sm font-semibold text-slate-950 hover:opacity-95">
                                    Kosárba
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-store-layout>
