@extends('layouts.auth')

@section('title', 'Login - Sistem Pendaftaran Pelatihan UNSRI')

@section('content')
<div class="auth">
    <div class="card">
        <div style="text-align:center">
        <img src="https://unsri.ac.id/images/lambang/979ca5b6-fc69-4efe-ac7b-82429127ef5b.png" 
                 alt="Logo UNSRI" style="width:60px">

            <h1 style="font-size:20px;margin-top:10px">Sistem Pendaftaran Pelatihan</h1>
            <p class="mu">Universitas Sriwijaya</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <label for="username">Username / email</label>
            <input id="username" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">

            @if ($errors->any())
                <div class="err">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="p" style="width:100%;margin-top:18px;padding:12px">Masuk ke Sistem</button>

            <p style="text-align:center;margin:14px 0 0">
                <a href="{{ route('register') }}">Daftar sebagai peserta</a>
            </p>
        </form>
    </div>

   
</div>
@endsection
