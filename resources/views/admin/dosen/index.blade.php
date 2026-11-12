@extends('layouts.app')

@section('title', 'Database Dosen - Pelatihan UNSRI')

@section('content')
<h1>Database Dosen (Master Data)</h1>
<p class="mu">Data ini digunakan untuk verifikasi dan auto-lookup pendaftar UNSRI pada formulir pendaftaran.</p>

<div class="card">
    <div class="row">
        <form method="GET" action="{{ route('admin.dosen.index') }}" style="display:flex;gap:8px;flex:1;flex-wrap:wrap">
            <input name="q" placeholder="Cari nama, NIDN, atau fakultas/instansi..." value="{{ request('q') }}">
            <button type="submit">Cari</button>
            @if (request()->filled('q'))
                <a href="{{ route('admin.dosen.index') }}"><button type="button">Reset</button></a>
            @endif
        </form>

        <button type="button" class="p" onclick="openCreateModal()">Tambah Dosen</button>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>NIDN / NUPTK</th>
                    <th>Nama Lengkap</th>
                    <th>Asal Instansi / Fakultas</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dosens as $d)
                    <tr>
                        <td><code>{{ $d->nidn }}</code></td>
                        <td><b>{{ $d->nama }}</b></td>
                        <td>{{ $d->instansi }}</td>
                        <td style="white-space:nowrap;text-align:right">
                            <button type="button" class="s" onclick='openEditModal(@json($d))'>Edit</button>
                            <form action="{{ route('admin.dosen.destroy', $d->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data dosen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="s d">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="mu" style="text-align:center;padding:20px">
                            Belum ada data dosen master.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal" id="dosenModal">
    <div class="card">
        <h2 id="modalTitle">Tambah Dosen</h2>
        <form id="dosenForm" method="POST">
            @csrf
            <div id="methodContainer"></div>

            <label for="f_nidn">NIDN / NUPTK</label>
            <input id="f_nidn" name="nidn" placeholder="Mis. 0001018001" required>

            <label for="f_nama">Nama Lengkap & Gelar</label>
            <input id="f_nama" name="nama" placeholder="Mis. Dr. Ahmad Fauzi, M.Kom." required>

            <label for="f_instansi">Asal Instansi / Fakultas</label>
            <input id="f_instansi" name="instansi" placeholder="Mis. UNSRI - Fakultas Ilmu Komputer" required>

            <div class="row" style="margin-top:16px;justify-content:flex-end">
                <button type="button" onclick="closeModal()">Batal</button>
                <button type="submit" class="p">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('dosenModal');
    const form = document.getElementById('dosenForm');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        title.textContent = 'Tambah Dosen';
        form.action = "{{ route('admin.dosen.store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        modal.classList.add('open');
    }

    function openEditModal(d) {
        title.textContent = 'Edit Dosen';
        form.action = `{{ url('admin/dosen') }}/${d.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        document.getElementById('f_nidn').value = d.nidn || '';
        document.getElementById('f_nama').value = d.nama || '';
        document.getElementById('f_instansi').value = d.instansi || '';

        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
    }
</script>
@endpush
@endsection
