@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="text-xl font-bold">{{ $product->name }}</div>
@endsection


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
        <div class="aspect-[4/3] bg-zinc-950 rounded-lg overflow-hidden grid place-items-center">
            @if($main)
                <img src="{{ asset('storage/' . ltrim($main->path, '/')) }}" class="w-full h-full object-cover" alt="">
            @else
                <div class="text-zinc-500 text-sm">Nincs kép</div>
            @endif
        </div>

        @if($imgs->count() > 1)
            <div class="mt-3 grid grid-cols-5 gap-2">
                @foreach($imgs as $img)
                    <img src="{{ asset('storage/' . ltrim($img->path, '/')) }}"
                         class="aspect-square object-cover rounded border border-zinc-800" alt="">
                @endforeach
            </div>
        @endif
    </div>

    <div class="space-y-3">
        <div class="text-zinc-400 text-sm">{{ $product->sku }}</div>
        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>

        <div class="text-zinc-300">
            Márka: <span class="font-semibold">{{ $product->brand?->name ?? '-' }}</span><br>
            Kategória: <span class="font-semibold">{{ $product->category?->name ?? '-' }}</span>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
            <div class="font-semibold mb-2">Kompatibilis járművek</div>
            <ul class="text-sm text-zinc-300 list-disc pl-5 space-y-1">
                @forelse($product->vehicles as $v)
                    <li>{{ $v->make }} {{ $v->model }} {{ $v->engine }} ({{ $v->year_from ?? '?' }}-{{ $v->year_to ?? '?' }})</li>
                @empty
                    <li>Nincs megadva.</li>
                @endforelse
            </ul>
        </div>

        @if($product->description)
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-4">
                <div class="font-semibold mb-2">Leírás</div>
                <div class="text-sm text-zinc-300 whitespace-pre-line">{{ $product->description }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
