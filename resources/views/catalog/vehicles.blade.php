@extends('layouts.app')
@section('title','Jármű kiválasztás')

@php
  $pickQuery = request()->query();
@endphp

@section('content')
<style>
  .page{display:flex;min-height:calc(100vh - 60px)}
  .side{width:270px;background:#1e3a8a;color:#fff}
  .sideTop{padding:14px;border-bottom:1px solid rgba(255,255,255,.12);display:flex;gap:10px;align-items:center}
  .sideTop img{height:34px}
  .sideSearch{padding:12px}
  .sideSearch input{width:100%;padding:10px 12px;border-radius:8px;border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.08);color:#fff}
  .sideNav a{display:block;padding:10px 14px;color:#fff;text-decoration:none}
  .sideNav a:hover{background:rgba(255,255,255,.10)}
  .main{flex:1;background:#fff;padding:14px}

  .wrap{border:1px solid #cbd5e1;background:#fff}
  .head{padding:8px 10px;border-bottom:1px solid #cbd5e1;background:#f1f5f9;font-weight:800;display:flex;justify-content:space-between}
  table{width:100%;border-collapse:collapse;font-size:13px}
  th{font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#334155;border-bottom:1px solid #cbd5e1;padding:10px;text-align:left}
  td{border-top:1px solid #e2e8f0;padding:10px;vertical-align:middle}
  tr:hover{background:#eff6ff}
  .rowlink{color:#0f172a;text-decoration:none;font-weight:700}
  .sub{font-size:12px;color:#475569;font-weight:600}
</style>

<div class="page">
  <aside class="side">
    <div class="sideTop">
      <img src="{{ asset('images/logo.png') }}" alt="Logo">
      <div style="font-weight:800">MiMaParts</div>
    </div>
    <div class="sideSearch">
      <input placeholder="Keresés (F2)" />
    </div>
    <nav class="sideNav">
      <a href="{{ route('catalog.index', $pickQuery) }}">← Vissza kiválasztóhoz</a>
      <a href="{{ route('catalog.index') }}">Új keresés</a>
    </nav>
  </aside>

  <main class="main">
    <div class="wrap">
      <div class="head">
        <div>Jármű részletek</div>
        <div style="font-size:12px;color:#475569">
          {{ $make }} / {{ $model }} / {{ $engine }} / {{ $year }} {{ $body ? '/ '.$body : '' }}
        </div>
      </div>

      <div style="overflow:auto">
        <table>
          <thead>
            <tr>
              <th style="width:80px">ID</th>
              <th>Modell</th>
              <th style="width:140px">Motor</th>
              <th style="width:140px">Kivitel</th>
              <th style="width:160px">Gyártási időszak</th>
              <th style="width:160px">Művelet</th>
            </tr>
          </thead>
          <tbody>
          @forelse($vehicles as $v)
            <tr>
              <td>#{{ $v->id }}</td>
              <td>
                <div style="font-weight:800">{{ $v->make }} {{ $v->model }}</div>
                <div class="sub">Válassz variánst (UNIX)</div>
              </td>
              <td>{{ $v->engine }}</td>
              <td>{{ $v->body_style ?? '—' }}</td>
              <td>{{ $v->year_from ?? '?' }} – {{ $v->year_to ?? '?' }}</td>
              <td>
                <a class="rowlink" href="{{ route('catalog.parts', $v) }}?{{ http_build_query($pickQuery) }}">
                  Alkatrészek →
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" style="padding:14px;color:#475569">Nincs találat erre a szűrésre.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
@endsection