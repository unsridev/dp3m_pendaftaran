@extends('layouts.app')

@section('title', 'Dashboard Admin - Pelatihan UNSRI')

@section('content')
<h1>Dashboard</h1>
<p class="mu">Ringkasan pendaftaran pelatihan.</p>

<div class="grid">
    <div class="stat">
        <b style="color:var(--wr)">{{ $stats['menunggu'] }}</b>
        Menunggu verifikasi
    </div>
    <div class="stat">
        <b style="color:var(--ok)">{{ $stats['terverifikasi'] }}</b>
        Terverifikasi
    </div>
    <div class="stat">
        <b style="color:var(--er)">{{ $stats['ditolak'] }}</b>
        Ditolak
    </div>
    <div class="stat">
        <b>{{ $stats['kelas'] }}</b>
        Kelas
    </div>
    <div class="stat">
        <b>{{ $stats['pengajar'] }}</b>
        Pengajar
    </div>
</div>

<div class="card" style="margin-top:20px">
    <h2>Aksi Cepat</h2>
    <div class="row">
        @if ($stats['menunggu'] > 0)
            <a href="{{ route('admin.verifikasi') }}">
                <button type="button" class="p">Verifikasi {{ $stats['menunggu'] }} Pendaftar Baru</button>
            </a>
        @endif
        <a href="{{ route('admin.peserta.index') }}">
            <button type="button">Lihat Data Peserta</button>
        </a>
        <a href="{{ route('admin.kelas.index') }}">
            <button type="button">Kelola Kelas & Plotting</button>
        </a>
        <a href="{{ route('admin.jadwal.index') }}">
            <button type="button">Atur Jadwal Perkuliahan</button>
        </a>
    </div>
</div>
@endsection
