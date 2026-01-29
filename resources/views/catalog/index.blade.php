@extends('layouts.app')
@section('title', 'Katalógus')

@php
    $categories = collect($categories ?? []);
    $brands     = collect($brands ?? []);
    $vehicles   = collect($vehicles ?? []);

    $catOptions = $categories->mapWithKeys(function ($c) use ($categories) {
        $label = $c->name;
        if (!empty($c->parent_id)) {
            $p = $categories->firstWhere('id', $c->parent_id);
            $label = ($p?->name ? $p->name . ' / ' : '') . $c->name;
        }
        return [$c->id => $label];
    });
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
    {{-- Szűrő (unixosan: doboz, sok input) --}}
    <aside class="lg:col-span-2">
        <form method="get" action="{{ route('catalog.index') }}"
              class="border border-slate-300 rounded-md p-3 bg-slate-50 space-y-3">
            <div class="text-sm font-semibold">Szűrők</div>

            <div>
                <label class="text-xs text-slate-600">Keresés</label>
                <input name="q" value="{{ request('q') }}"
                       class="mt-1 w-full rounded-md border border-slate-300 px-2 py-2 text-sm"
                       placeholder="pl. féktárcsa / 1K0...">
            </div>

            <div>
                <label class="text-xs text-slate-600">Kategória</label>
                <select name="category_id"
                        class="mt-1 w-full rounded-md border border-slate-300 px-2 py-2 text-sm">
                    <option value="">Összes</option>
                    @foreach($catOptions as $id => $label)
                        <option value="{{ $id }}" @selected((int)request('category_id') === (int)$id)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-slate-600">Márka</label>
                <select name="brand_id"
                        class="mt-1 w-full rounded-md border border-slate-300 px-2 py-2 text-sm">
                    <option value="">Összes</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected((int)request('brand_id') === (int)$b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-slate-600">Jármű</label>
                <select name="vehicle_id"
                        class="mt-1 w-full rounded-md border border-slate-300 px-2 py-2 text-sm">
                    <option value="">Összes</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" @selected((int)request('vehicle_id') === (int)$v->id)>
                            {{ $v->make }} {{ $v->model }} {{ $v->engine }} ({{ $v->year_from ?? '?' }}-{{ $v->year_to ?? '?' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock'))>
                Csak raktáron
            </label>

            <div class="flex gap-2">
                <button class="px-3 py-2 rounded-md bg-blue-600 text-white text-sm w-full hover:bg-blue-700">
                    Szűrés
                </button>
                <a href="{{ route('catalog.index') }}"
                   class="px-3 py-2 rounded-md border border-slate-300 text-sm w-full text-center bg-white hover:bg-slate-100">
                    Törlés
                </a>
            </div>
        </form>
    </aside>

    {{-- Lista/táblázat (unixos) --}}
    <section class="lg:col-span-3">
        <div class="border border-slate-300 rounded-md overflow-hidden">
            <div class="bg-slate-100 px-3 py-2 text-sm flex items-center justify-between">
                <div>Találatok: <b>{{ $products->total() }}</b></div>
                <div class="text-xs text-slate-600">
                    Oldal: {{ $products->currentPage() }} / {{ $products->lastPage() }}
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-white border-b border-slate-200">
                <tr class="text-left">
                    <th class="p-2 w-16">Kép</th>
                    <th class="p-2 w-40">Cikkszám</th>
                    <th class="p-2">Megnevezés</th>
                    <th class="p-2 w-40">Márka</th>
                    <th class="p-2 w-36">Kategória</th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @forelse($products as $p)
                    @php
                        $img = $p->images->sortBy('sort')->first();
                        $imgUrl = $img ? asset('storage/' . ltrim($img->path, '/')) : null;
                    @endphp
                    <tr class="border-t border-slate-100 hover:bg-slate-50">
                        <td class="p-2">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" class="h-10 w-14 object-cover border border-slate-200" alt="">
                            @else
                                <div class="h-10 w-14 bg-slate-100 border border-slate-200"></div>
                            @endif
                        </td>
                        <td class="p-2">
                            <div class="font-mono text-xs">{{ $p->sku }}</div>
                        </td>
                        <td class="p-2">
                            <a class="text-blue-700 hover:underline"
                               href="{{ route('product.show', $p) }}">
                                {{ $p->name }}
                            </a>
                            @if($p->oem_number)
                                <div class="text-xs text-slate-500">OEM: {{ $p->oem_number }}</div>
                            @endif
                        </td>
                        <td class="p-2">{{ $p->brand?->name }}</td>
                        <td class="p-2">{{ $p->category?->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-3 text-slate-500" colspan="5">Nincs találat.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </section>
</div>
@endsection
