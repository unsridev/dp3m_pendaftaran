@extends('layouts.app')

@section('title', 'Dashboard Peserta - Pelatihan UNSRI')

@section('content')
<h1>Halo, {{ $peserta->nama }}</h1>
<p class="mu">Akun Anda sudah terverifikasi dan aktif di sistem pelatihan.</p>

<div class="grid">
    <div class="stat">
        <b style="color:var(--pr)">
            {{ $peserta->kelas ? $peserta->kelas->nama : 'Belum Ada' }}
        </b>
        Kelas Anda
    </div>
    <div class="stat">
        <b>{{ $sesiCount }}</b>
        Sesi per pekan
    </div>
</div>

<div class="card" style="margin-top:20px">
    <h2>Informasi Pelatihan</h2>
    @if ($peserta->kelas)
        <p>Anda telah terdaftar di kelas <b>{{ $peserta->kelas->nama }} ({{ $peserta->kelas->kode }})</b>.</p>
        <div class="row" style="margin-top:14px">
            <a href="{{ route('peserta.jadwal') }}"><button type="button" class="p">Lihat Jadwal Kelas</button></a>
            <a href="{{ route('peserta.elearning') }}"><button type="button">Akun E-Learning</button></a>
            <a href="{{ route('peserta.wa') }}"><button type="button">Grup WhatsApp</button></a>
        </div>
    @else
        <p class="mu">Anda belum ditempatkan di kelas pelatihan oleh administrator. Silakan cek berkala atau hubungi panitia.</p>
    @endif
</div>
@endsection
