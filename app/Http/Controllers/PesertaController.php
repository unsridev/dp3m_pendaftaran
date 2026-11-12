<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class PesertaController extends Controller
{
    private function getPeserta()
    {
        return Auth::user()->peserta;
    }

    public function dashboard()
    {
        $peserta = $this->getPeserta();
        $sesiCount = $peserta && $peserta->kelas_id
            ? Jadwal::where('kelas_id', $peserta->kelas_id)->count()
            : 0;

        return view('peserta.dashboard', compact('peserta', 'sesiCount'));
    }

    public function jadwal()
    {
        $peserta = $this->getPeserta();
        $jadwals = $peserta && $peserta->kelas_id
            ? Jadwal::with(['kelas', 'pengajar'])->where('kelas_id', $peserta->kelas_id)->orderBy('hari')->get()
            : collect();

        $html = Blade::render(<<<'BLADE'
@extends('layouts.app')
@section('title', 'Jadwal Perkuliahan - Pelatihan UNSRI')
@section('content')
<h1>Jadwal Perkuliahan</h1>
<div class="card tw">
    @if ($peserta && $peserta->kelas)
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Ruang</th>
                    <th>Pengajar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwals as $j)
                    <tr>
                        <td><b>{{ $j->kelas ? $j->kelas->nama : '-' }}</b></td>
                        <td>{{ $j->hari }}</td>
                        <td>{{ $j->jam }}</td>
                        <td>{{ $j->ruang }}</td>
                        <td>{{ $j->pengajar ? $j->pengajar->nama : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="mu" style="text-align:center;padding:16px">Jadwal kelas belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    @else
        <span class="mu">Belum tersedia. Admin belum menempatkan Anda di kelas.</span>
    @endif
</div>
@endsection
BLADE
        , compact('peserta', 'jadwals'));

        return response($html);
    }

    public function elearning()
    {
        $peserta = $this->getPeserta();

        $html = Blade::render(<<<'BLADE'
@extends('layouts.app')
@section('title', 'Akun E-Learning - Pelatihan UNSRI')
@section('content')
<h1>Info Akun E-Learning</h1>
<div class="card">
    @if ($peserta && $peserta->el_username)
        <p style="margin-bottom:12px">
            Username: <b>{{ $peserta->el_username }}</b>
            <button type="button" class="s" onclick="copyText('{{ $peserta->el_username }}')">Salin</button>
        </p>
        <p>
            Password: <b id="pwDisplay">••••••••</b>
            <button type="button" class="s" onclick="togglePassword()">Tampilkan/sembunyikan</button>
            <button type="button" class="s" onclick="copyText('{{ $peserta->el_password }}')">Salin</button>
        </p>
        <script>
            let showPw = false;
            function togglePassword() {
                showPw = !showPw;
                document.getElementById('pwDisplay').textContent = showPw ? '{{ $peserta->el_password }}' : '••••••••';
            }
        </script>
    @else
        <span class="mu">Belum tersedia. Admin belum mengisi akun e-learning Anda.</span>
    @endif
</div>
@endsection
BLADE
        , compact('peserta'));

        return response($html);
    }

    public function wa()
    {
        $peserta = $this->getPeserta();
        $waLink = $peserta->wa ?: ($peserta->kelas ? $peserta->kelas->wa : null);

        $html = Blade::render(<<<'BLADE'
@extends('layouts.app')
@section('title', 'Grup WhatsApp - Pelatihan UNSRI')
@section('content')
<h1>Grup WhatsApp</h1>
<div class="card">
    @if ($waLink)
        <p>Silakan klik tautan di bawah ini untuk bergabung ke grup WhatsApp resmi kelas pelatihan:</p>
        <div style="margin-top:14px">
            <a href="{{ $waLink }}" target="_blank" rel="noopener">
                <button type="button" class="p" style="background:#16a34a;border-color:#16a34a">Gabung grup</button>
            </a>
        </div>
    @else
        <span class="mu">Belum tersedia. Admin belum mengisi link grup WhatsApp kelas.</span>
    @endif
</div>
@endsection
BLADE
        , compact('peserta', 'waLink'));

        return response($html);
    }

    public function profil()
    {
        $peserta = $this->getPeserta();

        $html = Blade::render(<<<'BLADE'
@extends('layouts.app')
@section('title', 'Profil Peserta - Pelatihan UNSRI')
@section('content')
<h1>Profil</h1>
<div class="card tw">
    <table>
        <tr><th style="width:180px">Nama Lengkap</th><td><b>{{ $peserta->nama }}</b></td></tr>
        <tr><th>NIK</th><td>{{ $peserta->nik }}</td></tr>
        <tr><th>NIDN / NUPTK</th><td>{{ $peserta->nidn ?: '-' }}</td></tr>
        <tr><th>No HP (WhatsApp)</th><td>{{ $peserta->hp }}</td></tr>
        <tr><th>Email Instansi</th><td>{{ $peserta->email }}</td></tr>
        <tr><th>Tipe Peserta</th><td><span class="b {{ $peserta->tipe === 'Dosen UNSRI' ? 'ok' : 'wr' }}">{{ $peserta->tipe }}</span></td></tr>
        <tr><th>Kelas</th><td>{{ $peserta->kelas ? $peserta->kelas->nama . ' (' . $peserta->kelas->kode . ')' : 'Belum ditempatkan' }}</td></tr>
        <tr><th>Status</th><td><span class="b ok">{{ $peserta->status }}</span></td></tr>
    </table>
</div>
@endsection
BLADE
        , compact('peserta'));

        return response($html);
    }
}
