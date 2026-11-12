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

    <div class="card demo" style="width:800px;margin:auto auto">
        <b>Akun demo</b>
        <table>
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Admin</td>
                    <td><code>admin</code></td>
                    <td><code>admin123</code></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Pengajar</td>
                    <td><code>ahmad@unsri.ac.id</code></td>
                    <td><code>pengajar123</code></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>Peserta</td>
                    <td><code>siti@unsri.ac.id</code></td>
                    <td><code>peserta123</code></td>
                    <td><span class="b ok">Terverifikasi</span></td>
                </tr>
                <tr>
                    <td>Peserta</td>
                    <td><code>budi@unsri.ac.id</code></td>
                    <td><code>peserta123</code></td>
                    <td><span class="b wr">Menunggu</span></td>
                </tr>
                <tr>
                    <td>Peserta</td>
                    <td><code>rina@univx.ac.id</code></td>
                    <td><code>peserta123</code></td>
                    <td><span class="b er">Ditolak</span></td>
                </tr>
            </tbody>
        </table>
        <p class="mu" style="margin-top:10px">
            Data dosen untuk uji pendaftaran: <code>0001018001</code>, <code>0007078007</code>, <code>0011118011</code> (lainnya di menu Database Dosen).
        </p>
        <form action="{{ route('demo.reset') }}" method="POST" onsubmit="return confirm('Reset semua data demo ke awal?')" style="margin-top:8px">
            @csrf
            <button type="submit" class="s d">Reset data demo</button>
        </form>
    </div>
</div>
@endsection
