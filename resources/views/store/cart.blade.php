<x-store-layout title="Kosár – MiMaParts">
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xl font-semibold">Kosár</div>
                    <div class="text-sm text-slate-300">
                        Tételek: <span class="text-white font-semibold">{{ $totals['items_count'] }}</span>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-sm text-slate-300">Összesen</div>
                    <div class="text-2xl font-semibold">
                        {{ number_format($totals['subtotal_gross'], 0, '', ' ') }} {{ $totals['currency'] }}
                    </div>
                </div>
            </div>

            @if(session('ok'))
                <div class="mt-4 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-emerald-200">
                    {{ session('ok') }}
                </div>
            @endif
        </div>

        @if($cart->items->isEmpty())
            <div class="rounded-3xl border border-white/10 bg-white/5 p-10 text-slate-300">
                A kosarad üres.
            </div>
        @else
            <div class="space-y-3">
                @foreach($cart->items as $item)
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <div class="text-xs text-slate-400">SKU: <span class="text-slate-200">{{ $item->product_sku }}</span></div>
                                <div class="font-semibold">{{ $item->product_name }}</div>
                                <div class="text-sm text-slate-300">
                                    Egységár: {{ number_format($item->price_gross, 0, '', ' ') }} {{ $item->currency }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('cart.qty') }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                                    <input type="number" name="qty" min="0" value="{{ $item->qty }}"
                                           class="w-24 rounded-2xl bg-slate-900/60 border border-white/10 px-4 py-2 text-sm outline-none focus:border-white/20" />

                                    <button class="rounded-2xl bg-white/10 px-4 py-2 text-sm hover:bg-white/15">
                                        Mentés
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <button class="rounded-2xl bg-rose-500/15 text-rose-200 border border-rose-500/20 px-4 py-2 text-sm hover:bg-rose-500/20">
                                        Törlés
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3 text-right text-sm text-slate-300">
                            Sorösszeg:
                            <span class="text-white font-semibold">
                                {{ number_format($item->price_gross * $item->qty, 0, '', ' ') }} {{ $item->currency }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 flex items-center justify-between">
                <div class="text-sm text-slate-300">Következő lépés: checkout (szállítás + számlázás).</div>
                <button class="rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 hover:opacity-95">
                    Tovább a pénztárhoz
                </button>
            </div>
        @endif
    </div>
</x-store-layout>
