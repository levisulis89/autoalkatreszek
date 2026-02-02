@extends('layouts.app')
@section('title','Alkatrészek')

@section('content')
<style>
  .page{display:flex;min-height:calc(100vh - 60px)}
  .side{width:270px;background:#1e3a8a;color:#fff}
  .sideTop{padding:14px;border-bottom:1px solid rgba(255,255,255,.12);display:flex;gap:10px;align-items:center}
  .sideTop img{height:34px}
  .sideNav a{display:block;padding:10px 14px;color:#fff;text-decoration:none}
  .sideNav a:hover{background:rgba(255,255,255,.10)}
  .main{flex:1;background:#fff;padding:14px}

  .wrap{border:1px solid #cbd5e1;background:#fff}
  .head{padding:8px 10px;border-bottom:1px solid #cbd5e1;background:#f1f5f9;font-weight:800;display:flex;justify-content:space-between;align-items:center}
  .pill{display:inline-block;border:1px solid #cbd5e1;border-radius:999px;padding:2px 8px;font-size:12px;background:#fff}
  table{width:100%;border-collapse:collapse;font-size:13px}
  th{font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#334155;border-bottom:1px solid #cbd5e1;padding:10px;text-align:left}
  td{border-top:1px solid #e2e8f0;padding:10px;vertical-align:middle}
  tr:hover{background:#eff6ff}
</style>

<div class="page">
  <aside class="side">
    <div class="sideTop">
      <img src="{{ asset('images/logo.png') }}" alt="Logo">
      <div style="font-weight:800">MiMaParts</div>
    </div>
    <nav class="sideNav">
      <a href="{{ route('catalog.vehicle', request()->query()) }}">← Vissza járműlistára</a>
      <a href="{{ route('catalog.index', request()->query()) }}">← Vissza kiválasztóhoz</a>
      <a href="{{ route('catalog.index') }}">Új keresés</a>
    </nav>
  </aside>

  <main class="main">
    <div class="wrap">
      <div class="head">
        <div>
          Alkatrészek – <span style="font-weight:900">#{{ $vehicle->id }}</span>
          <span class="pill" style="margin-left:8px">
            {{ $vehicle->make }} {{ $vehicle->model }} / {{ $vehicle->engine }} / {{ $vehicle->year_from }}–{{ $vehicle->year_to }}
          </span>
        </div>

        <form method="get" style="display:flex;gap:10px;align-items:center">
          {{-- tartsuk meg a korábbi query-t (make/model/engine/year/body) --}}
          @foreach(request()->except(['q','in_stock','page']) as $k => $v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
          @endforeach

          <input name="q" value="{{ $q }}" placeholder="Cikkszám / megnevezés"
                 style="padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;width:320px">

          <label style="display:flex;gap:6px;align-items:center;font-size:13px;color:#334155">
            <input type="checkbox" name="in_stock" value="1" @checked($inStock)>
            Csak raktáron
          </label>

          <button type="submit" class="pill" style="font-weight:800">Keres</button>
        </form>
      </div>

      <div style="overflow:auto">
        <table>
          <thead>
            <tr>
              <th style="width:110px">Kép</th>
              <th style="width:170px">Cikkszám</th>
              <th>Megnevezés</th>
              <th style="width:170px">Márka</th>
              <th style="width:170px">Kategória</th>
            </tr>
          </thead>
          <tbody>
          @forelse($products as $p)
            @php
              $img = $p->images->sortBy('sort')->first();
              $imgUrl = $img ? asset('storage/' . ltrim($img->path, '/')) : null;
            @endphp
            <tr>
              <td>
                @if($imgUrl)
                  <img src="{{ $imgUrl }}" style="height:42px;width:72px;object-fit:cover;border:1px solid #cbd5e1" alt="">
                @else
                  <div style="height:42px;width:72px;background:#f1f5f9;border:1px solid #cbd5e1"></div>
                @endif
              </td>
              <td>
                <div style="font-family:ui-monospace, Menlo, Consolas, monospace;font-size:12px;font-weight:900;color:#0f172a">{{ $p->sku }}</div>
                @if($p->oem_number)
                  <div style="font-size:12px;color:#475569">OEM: {{ $p->oem_number }}</div>
                @endif
              </td>
              <td>
                <a href="{{ route('product.show',$p) }}" style="font-weight:900;color:#1d4ed8;text-decoration:none">
                  {{ $p->name }}
                </a>
              </td>
              <td>{{ $p->brand?->name ?? '—' }}</td>
              <td>{{ $p->category?->name ?? '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="5" style="padding:14px;color:#475569">Nincs találat.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div style="padding:10px;border-top:1px solid #cbd5e1">
        {{ $products->links() }}
      </div>
    </div>
  </main>
</div>
@endsection