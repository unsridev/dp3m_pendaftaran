@extends('layouts.app')

@section('title', 'Data Peserta - Pelatihan UNSRI')

@section('content')
<h1>Data Peserta</h1>

<div class="card">
    <div class="row">
        <form method="GET" action="{{ route('admin.peserta.index') }}" style="display:flex;gap:8px;flex:1;flex-wrap:wrap">
            <input name="q" placeholder="Cari nama, email, NIK, NIDN..." value="{{ request('q') }}">
            <select name="status" onchange="this.form.submit()" style="max-width:180px">
                <option value="">Semua status</option>
                <option value="Menunggu Verifikasi" {{ request('status') === 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="Terverifikasi" {{ request('status') === 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit">Cari</button>
            @if (request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.peserta.index') }}"><button type="button">Reset</button></a>
            @endif
        </form>

        <button type="button" class="p" onclick="openCreateModal()">Tambah Peserta</button>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIDN</th>
                    <th>Email</th>
                    <th>Tipe</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>E-Learning</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesertas as $p)
                    <tr>
                        <td>
                            <b>{{ $p->nama }}</b><br>
                            <small class="mu">NIK: {{ $p->nik }}</small>
                        </td>
                        <td>{{ $p->nidn ?: '-' }}</td>
                        <td>{{ $p->email }}</td>
                        <td>
                            <span class="b {{ $p->tipe === 'Dosen UNSRI' ? 'ok' : 'wr' }}">{{ $p->tipe }}</span>
                        </td>
                        <td>{{ $p->kelas ? $p->kelas->nama : '-' }}</td>
                        <td>
                            <span class="b {{ $p->status === 'Terverifikasi' ? 'ok' : ($p->status === 'Ditolak' ? 'er' : 'wr') }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td>
                            @if ($p->el_username)
                                <code>{{ $p->el_username }}</code>
                            @else
                                <span class="mu">Belum diisi</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;text-align:right">
                            <button type="button" class="s" onclick='openEditModal(@json($p))'>Edit</button>
                            <form action="{{ route('admin.peserta.destroy', $p->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data peserta ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="s d">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="mu" style="text-align:center;padding:20px">
                            Belum ada data peserta. Klik Tambah Peserta untuk membuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal" id="pesertaModal">
    <div class="card">
        <h2 id="modalTitle">Tambah Peserta</h2>
        <form id="pesertaForm" method="POST">
            @csrf
            <div id="methodContainer"></div>

            <label for="f_nik">NIK</label>
            <input id="f_nik" name="nik" maxlength="16" required>

            <label for="f_nidn">NIDN / NUPTK</label>
            <input id="f_nidn" name="nidn">

            <label for="f_nama">Nama Lengkap</label>
            <input id="f_nama" name="nama" required>

            <label for="f_hp">No HP (WhatsApp)</label>
            <input id="f_hp" name="hp" required>

            <label for="f_email">Email Instansi</label>
            <input id="f_email" name="email" type="email" required>

            <label for="f_tipe">Tipe Peserta</label>
            <select id="f_tipe" name="tipe" required>
                <option value="Dosen UNSRI">Dosen UNSRI</option>
                <option value="Dosen Luar">Dosen Luar</option>
            </select>

            <label for="f_status">Status Pendaftaran</label>
            <select id="f_status" name="status" required>
                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                <option value="Terverifikasi">Terverifikasi</option>
                <option value="Ditolak">Ditolak</option>
            </select>

            <label for="f_kelas_id">Penempatan Kelas</label>
            <select id="f_kelas_id" name="kelas_id">
                <option value="">-- Belum Ditempatkan --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->kode }})</option>
                @endforeach
            </select>

            <label for="f_el_username">Username E-Learning (opsional)</label>
            <input id="f_el_username" name="el_username">

            <label for="f_el_password">Password E-Learning (opsional)</label>
            <input id="f_el_password" name="el_password">

            <label for="f_wa">Link Khusus WhatsApp (opsional)</label>
            <input id="f_wa" name="wa" placeholder="https://chat.whatsapp.com/...">

            <div class="row" style="margin-top:16px;justify-content:flex-end">
                <button type="button" onclick="closeModal()">Batal</button>
                <button type="submit" class="p">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('pesertaModal');
    const form = document.getElementById('pesertaForm');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        title.textContent = 'Tambah Peserta';
        form.action = "{{ route('admin.peserta.store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        document.getElementById('f_tipe').value = 'Dosen UNSRI';
        document.getElementById('f_status').value = 'Terverifikasi';
        modal.classList.add('open');
    }

    function openEditModal(p) {
        title.textContent = 'Edit Peserta';
        form.action = `{{ url('admin/peserta') }}/${p.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        document.getElementById('f_nik').value = p.nik || '';
        document.getElementById('f_nidn').value = p.nidn || '';
        document.getElementById('f_nama').value = p.nama || '';
        document.getElementById('f_hp').value = p.hp || '';
        document.getElementById('f_email').value = p.email || '';
        document.getElementById('f_tipe').value = p.tipe || 'Dosen UNSRI';
        document.getElementById('f_status').value = p.status || 'Terverifikasi';
        document.getElementById('f_kelas_id').value = p.kelas_id || '';
        document.getElementById('f_el_username').value = p.el_username || '';
        document.getElementById('f_el_password').value = p.el_password || '';
        document.getElementById('f_wa').value = p.wa || '';

        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
    }
</script>
@endpush
@endsection
