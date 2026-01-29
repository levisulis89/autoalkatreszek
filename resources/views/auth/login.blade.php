@extends('layouts.app')
@section('title','Bejelentkezés')

@section('content')
<div class="max-w-md border border-slate-200 bg-white p-4">
    <h1 class="font-semibold text-lg mb-3">Bejelentkezés</h1>

    <form method="post" action="{{ route('auth.login.post') }}" class="space-y-3">
        @csrf
        <div>
            <label class="text-sm">Email</label>
            <input name="email" value="{{ old('email') }}" class="w-full border border-slate-300 px-3 py-2" required>
            @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="text-sm">Jelszó</label>
            <input type="password" name="password" class="w-full border border-slate-300 px-3 py-2" required>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1"> Emlékezz rám
        </label>
        <button class="px-4 py-2 bg-blue-600 text-white">Belépés</button>
    </form>
</div>
@endsection
