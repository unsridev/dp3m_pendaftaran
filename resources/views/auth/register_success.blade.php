@extends('layouts.auth')

@section('title', 'Pendaftaran Terkirim - Pelatihan UNSRI')

@section('content')
<div class="auth">
    <div class="card">
        <h1>Pendaftaran terkirim</h1>
        <p style="margin: 12px 0;">Status Anda: <span class="b wr">Menunggu Verifikasi</span></p>
        <p class="mu">Akun Anda belum bisa dipakai login sebelum diverifikasi dan disetujui oleh admin.</p>
        <div style="background:var(--lb);color:var(--pr);padding:12px;border-radius:8px;margin:16px 0;">
            Password awal akun Anda: <b>peserta123</b>
        </div>
        <div style="margin-top:20px">
            <a href="{{ route('login') }}">
                <button type="button" class="p" style="width:100%">Ke halaman login</button>
            </a>
        </div>
    </div>
</div>
@endsection
