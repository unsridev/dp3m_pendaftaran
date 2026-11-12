<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class PengajarController extends Controller
{
    private function getPengajar()
    {
        return Auth::user()->pengajar;
    }

    public function jadwal()
    {
        $pengajar = $this->getPengajar();
        $jadwals = $pengajar
            ? Jadwal::with('kelas')->where('pengajar_id', $pengajar->id)->orderBy('hari')->get()
            : collect();

        $html = Blade::render(<<<'BLADE'
@extends('layouts.app')
@section('title', 'Jadwal Mengajar - Pelatihan UNSRI')
@section('content')
<h1>Jadwal Mengajar</h1>
<div class="card tw">
    <table>
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Ruang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwals as $j)
                <tr>
                    <td><b>{{ $j->kelas ? $j->kelas->nama . ' (' . $j->kelas->kode . ')' : '-' }}</b></td>
                    <td>{{ $j->hari }}</td>
                    <td>{{ $j->jam }}</td>
                    <td>{{ $j->ruang }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="mu" style="text-align:center;padding:16px">Belum ada jadwal mengajar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
BLADE
        , compact('pengajar', 'jadwals'));

        return response($html);
    }

    public function peserta()
    {
        $pengajar = $this->getPengajar();
        if (!$pengajar) {
            $kelasList = collect();
        } else {
            $kelasIds = Jadwal::where('pengajar_id', $pengajar->id)->pluck('kelas_id')->unique();
            $kelasList = Kelas::with(['pesertas' => function ($q) {
                $q->where('status', 'Terverifikasi');
            }])->whereIn('id', $kelasIds)->get();
        }

        $html = Blade::render(<<<'BLADE'
@extends('layouts.app')
@section('title', 'Peserta Kelas - Pelatihan UNSRI')
@section('content')
<h1>Peserta Kelas</h1>
@forelse ($kelasList as $k)
    <div class="card">
        <h2>{{ $k->nama }} ({{ $k->kode }}) - {{ $k->pesertas->count() }} Peserta</h2>
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($k->pesertas as $p)
                        <tr>
                            <td><b>{{ $p->nama }}</b></td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->hp }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="mu">Belum ada peserta di kelas ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card mu" style="text-align:center;padding:20px">
        Anda belum mengampu kelas.
    </div>
@endforelse
@endsection
BLADE
        , compact('pengajar', 'kelasList'));

        return response($html);
    }
}
