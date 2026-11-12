@extends('layouts.app')

@section('title', 'Plotting Peserta - ' . $kelas->nama)

@section('content')
<p><a href="{{ route('admin.kelas.index') }}">← Kembali ke Data Kelas</a></p>
<h1>Peserta Kelas: {{ $kelas->nama }} ({{ $kelas->kode }})</h1>
<p class="mu">Centang peserta terverifikasi yang akan dimasukkan ke kelas ini. Kuota: <b>{{ $kelas->kuota }}</b>.</p>

<!-- Form 1: Masukkan Peserta -->
<div class="card">
    <h2>1. Pilih peserta untuk dimasukkan ke {{ $kelas->nama }}</h2>

    <div class="row">
        <input id="searchCandidate" placeholder="Cari nama, NIK, atau NIDN..." oninput="filterCandidates()">

        <select id="filterType" onchange="window.location.href = '{{ route('admin.kelas.plotting', $kelas->id) }}?filter=' + this.value" style="max-width:260px">
            <option value="belum" {{ $filter === 'belum' ? 'selected' : '' }}>Hanya yang belum punya kelas</option>
            <option value="semua" {{ $filter === 'semua' ? 'selected' : '' }}>Semua (termasuk pindah kelas)</option>
        </select>

        <button type="submit" form="formAdd" class="p">Masukkan Terpilih</button>
    </div>

    <form id="formAdd" action="{{ route('admin.kelas.plotting.add', $kelas->id) }}" method="POST">
        @csrf
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th style="width:36px">
                            <input type="checkbox" id="checkAllCandidates" onchange="toggleSelectAll(this, '.cand-check')">
                        </th>
                        <th>Nama</th>
                        <th>NIDN/NUPTK</th>
                        <th>Tipe</th>
                        <th>Kelas Saat Ini</th>
                    </tr>
                </thead>
                <tbody id="candidateBody">
                    @forelse ($candidates as $c)
                        <tr class="cand-row">
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $c->id }}" class="cand-check">
                            </td>
                            <td>
                                <b>{{ $c->nama }}</b><br>
                                <small class="mu">NIK: {{ $c->nik }}</small>
                            </td>
                            <td>{{ $c->nidn ?: '-' }}</td>
                            <td>
                                <span class="b {{ $c->tipe === 'Dosen UNSRI' ? 'ok' : 'wr' }}">{{ $c->tipe }}</span>
                            </td>
                            <td>{{ $c->kelas ? $c->kelas->nama : '<span class="mu">Belum ada</span>' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="mu" style="text-align:center;padding:16px">
                                Tidak ada peserta terverifikasi yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

<!-- Form 2: Anggota Kelas -->
<div class="card">
    <div class="row" style="margin-bottom:12px">
        <h2 style="margin:0;flex:1">
            Anggota Kelas ({{ $kelas->pesertas->count() }} / {{ $kelas->kuota }})
        </h2>
        <button type="submit" form="formRemove" class="d">Keluarkan Terpilih</button>
    </div>

    <form id="formRemove" action="{{ route('admin.kelas.plotting.remove', $kelas->id) }}" method="POST">
        @csrf
        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th style="width:36px">
                            <input type="checkbox" id="checkAllMembers" onchange="toggleSelectAll(this, '.mem-check')">
                        </th>
                        <th>Nama</th>
                        <th>NIDN/NUPTK</th>
                        <th>Tipe</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelas->pesertas as $m)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $m->id }}" class="mem-check">
                            </td>
                            <td>
                                <b>{{ $m->nama }}</b><br>
                                <small class="mu">NIK: {{ $m->nik }}</small>
                            </td>
                            <td>{{ $m->nidn ?: '-' }}</td>
                            <td>
                                <span class="b {{ $m->tipe === 'Dosen UNSRI' ? 'ok' : 'wr' }}">{{ $m->tipe }}</span>
                            </td>
                            <td>{{ $m->hp }} | {{ $m->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="mu" style="text-align:center;padding:16px">
                                Belum ada anggota di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleSelectAll(master, selector) {
        document.querySelectorAll(selector).forEach(cb => {
            cb.checked = master.checked;
        });
    }

    function filterCandidates() {
        const query = document.getElementById('searchCandidate').value.toLowerCase();
        const rows = document.querySelectorAll('.cand-row');
        rows.forEach(r => {
            r.style.display = r.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
