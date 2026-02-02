@extends('layouts.app')
@section('title','Katalógus')

@php
    function q(array $arr = []) { return request()->fullUrlWithQuery($arr); }

    $make   = $make   ?? '';
    $model  = $model  ?? '';
    $engine = $engine ?? '';
    $year   = $year   ?? null;
    $body   = $body   ?? '';

    $makes   = $makes   ?? collect();
    $models  = $models  ?? collect();
    $engines = $engines ?? collect();
    $years   = $years   ?? collect();
    $bodies  = $bodies  ?? collect();

    $ready = (bool)($ready ?? false);

    $colModelsDisabled  = ($make === '');
    $colEnginesDisabled = ($make === '' || $model === '');
    $colYearsDisabled   = ($make === '' || $model === '' || $engine === '');
    $colBodiesDisabled  = (!$year);

    $cntMakes   = $makes->count();
    $cntModels  = $models->count();
    $cntEngines = $engines->count();
    $cntYears   = $years->count();
    $cntBodies  = $bodies->count();
@endphp

@section('content')

<style>
  .page{display:flex;min-height:calc(100vh - 60px)}
  .side{width:270px;background:#1e3a8a;color:#fff;flex:0 0 auto}
  .sideTop{padding:14px;border-bottom:1px solid rgba(255,255,255,.12);display:flex;gap:10px;align-items:center}
  .sideTop img{height:34px}
  .sideSearch{padding:12px}
  .sideSearch input{width:100%;padding:10px 12px;border-radius:8px;border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.08);color:#fff}
  .sideNav{padding:8px 0}
  .sideNav a{display:block;padding:10px 14px;color:#fff;text-decoration:none}
  .sideNav a:hover{background:rgba(255,255,255,.10)}
  .main{flex:1;background:#fff;padding:14px}

  .ux-wrap{border:1px solid #cbd5e1;background:#fff}
  .ux-top{display:flex;justify-content:space-between;align-items:center;font-size:13px;margin-bottom:8px}
  .ux-pill{display:inline-block;border:1px solid #cbd5e1;border-radius:999px;padding:2px 8px;font-size:12px;background:#fff}
  .ux-blue{background:#1e3a8a;color:#fff}
  .ux-blue2{background:#0f2d6b;color:#fff}
  .ux-h{padding:6px 10px;border-bottom:1px solid #cbd5e1;font-weight:700;font-size:13px;display:flex;justify-content:space-between;align-items:center}
  .ux-col{border-right:1px solid #cbd5e1}
  .ux-col:last-child{border-right:0}
  .ux-list{max-height:460px;overflow:auto}
  .ux-item{display:flex;justify-content:space-between;gap:8px;padding:7px 10px;border-bottom:1px solid #e2e8f0;font-size:13px;color:#0f172a;text-decoration:none}
  .ux-item:hover{background:#eff6ff}
  .ux-item.active{background:#2563eb;color:#fff;font-weight:700}
  .ux-item.active:hover{background:#1d4ed8}
  .ux-dis{opacity:.45;pointer-events:none}
</style>

<div class="page">

    {{-- BAL OLDALI UNIX MENÜ --}}
    <aside class="side">
        <div class="sideTop">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <div style="font-weight:800;letter-spacing:.02em">MiMaParts</div>
        </div>

        <div class="sideSearch">
            <input placeholder="Keresés a menüben (F4)" />
        </div>

        <nav class="sideNav">
            <a href="{{ route('catalog.index') }}">WebShop</a>
            <a href="#">Főoldal</a>
            <a href="#">Rólunk</a>
            <a href="#">Kapcsolat</a>
        </nav>
    </aside>

    {{-- JOBB OLDAL: KIVÁLASZTÓ --}}
    <main class="main">

        {{-- AUTO ÁTDOBÁS JÁRMŰ LISTÁRA, HA KÉSZ --}}
        @if($ready)
            <script>
                // UNIX: kész a lépcső -> át a jármű(variáns) listára
                window.location.href = @json(route('catalog.vehicle', request()->query()));
            </script>

            <div class="ux-wrap" style="padding:14px;font-size:14px">
                Kész. Átdoblak a <b>jármű kiválasztás</b> oldalra…
            </div>
        @endif

        <div class="ux-top">
            <div class="text-slate-800">
                <span style="font-weight:700">Kiválasztás:</span>
                <span style="margin-left:8px;color:#475569">
                    {{ $make ?: 'Gyártó' }} →
                    {{ $model ?: 'Modell' }} →
                    {{ $engine ?: 'Motor' }} →
                    {{ $year ?: 'Év' }} →
                    {{ $body ?: 'Kivitel' }}
                </span>
            </div>

            <a href="{{ route('catalog.index') }}" class="ux-pill" style="font-weight:700">Alaphelyzet</a>
        </div>

        <div class="ux-wrap">
            <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr))">

                {{-- 1 Gyártó --}}
                <div class="ux-col">
                    <div class="ux-h ux-blue">
                        <span>Gyártó</span>
                        <span class="ux-pill" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff">{{ $cntMakes }}</span>
                    </div>
                    <div class="ux-list">
                        @foreach($makes as $m)
                            <a href="{{ q(['make'=>$m,'model'=>null,'engine'=>null,'year'=>null,'body'=>null]) }}"
                               class="ux-item {{ $make===$m ? 'active' : '' }}">
                                <span>{{ $m }}</span><span></span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- 2 Modell --}}
                <div class="ux-col">
                    <div class="ux-h ux-blue2">
                        <span>Modell</span>
                        <span class="ux-pill" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff">{{ $cntModels }}</span>
                    </div>
                    <div class="ux-list {{ $colModelsDisabled ? 'ux-dis' : '' }}">
                        @if($colModelsDisabled)
                            <div style="padding:12px;color:#64748b;font-size:13px">Válassz gyártót.</div>
                        @else
                            @foreach($models as $m)
                                <a href="{{ q(['model'=>$m,'engine'=>null,'year'=>null,'body'=>null]) }}"
                                   class="ux-item {{ $model===$m ? 'active' : '' }}">
                                    <span>{{ $m }}</span><span></span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- 3 Motor --}}
                <div class="ux-col">
                    <div class="ux-h ux-blue2">
                        <span>Motor</span>
                        <span class="ux-pill" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff">{{ $cntEngines }}</span>
                    </div>
                    <div class="ux-list {{ $colEnginesDisabled ? 'ux-dis' : '' }}">
                        @if($colEnginesDisabled)
                            <div style="padding:12px;color:#64748b;font-size:13px">Válassz gyártót + modellt.</div>
                        @else
                            @foreach($engines as $e)
                                <a href="{{ q(['engine'=>$e,'year'=>null,'body'=>null]) }}"
                                   class="ux-item {{ $engine===$e ? 'active' : '' }}">
                                    <span>{{ $e }}</span><span></span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- 4 Év --}}
                <div class="ux-col">
                    <div class="ux-h ux-blue2">
                        <span>Év</span>
                        <span class="ux-pill" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff">{{ $cntYears }}</span>
                    </div>
                    <div class="ux-list {{ $colYearsDisabled ? 'ux-dis' : '' }}">
                        @if($colYearsDisabled)
                            <div style="padding:12px;color:#64748b;font-size:13px">Válassz motort.</div>
                        @else
                            @foreach($years as $y)
                                <a href="{{ q(['year'=>$y,'body'=>null]) }}"
                                   class="ux-item {{ (int)$year===(int)$y ? 'active' : '' }}">
                                    <span>{{ $y }}</span><span></span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- 5 Kivitel --}}
                <div>
                    <div class="ux-h ux-blue2">
                        <span>Kivitel</span>
                        <span class="ux-pill" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.25);color:#fff">{{ $cntBodies }}</span>
                    </div>
                    <div class="ux-list {{ $colBodiesDisabled ? 'ux-dis' : '' }}">
                        @if($colBodiesDisabled)
                            <div style="padding:12px;color:#64748b;font-size:13px">Válassz évjáratot.</div>
                        @else
                            <a href="{{ q(['body'=>null]) }}" class="ux-item {{ $body==='' ? 'active' : '' }}">
                                <span>Mindegy</span><span></span>
                            </a>
                            @forelse($bodies as $b)
                                <a href="{{ q(['body'=>$b]) }}" class="ux-item {{ $body===$b ? 'active' : '' }}">
                                    <span>{{ $b }}</span><span></span>
                                </a>
                            @empty
                                <div style="padding:12px;color:#64748b;font-size:13px">Nincs kivitel adat.</div>
                            @endforelse
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="ux-wrap" style="margin-top:12px">
            <div class="ux-h" style="background:#f1f5f9">
                <span>Találatok</span>
                <span style="font-size:12px;color:#475569">Válaszd végig és átvisz a járműlistára</span>
            </div>
            <div style="padding:12px;color:#475569;font-size:13px">
                Itt nincs terméklista. A UNIX-nál is előbb <b>jármű variánst</b> választasz, és csak utána jönnek az alkatrészek.
            </div>
        </div>

    </main>
</div>
@endsection