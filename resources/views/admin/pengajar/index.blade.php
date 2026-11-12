@extends('layouts.app')

@section('title', 'Data Pengajar - Pelatihan UNSRI')

@section('content')
<h1>Data Pengajar</h1>

<div class="card">
    <div class="row">
        <button type="button" class="p" onclick="openCreateModal()">Tambah Pengajar</button>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIDN</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Bidang Keahlian</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajars as $p)
                    <tr>
                        <td><b>{{ $p->nama }}</b></td>
                        <td>{{ $p->nidn ?: '-' }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->hp ?: '-' }}</td>
                        <td>{{ $p->bidang ?: '-' }}</td>
                        <td style="white-space:nowrap;text-align:right">
                            <button type="button" class="s" onclick='openEditModal(@json($p))'>Edit</button>
                            <form action="{{ route('admin.pengajar.destroy', $p->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pengajar ini? Akun login terkait juga akan dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="s d">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="mu" style="text-align:center;padding:20px">
                            Belum ada data pengajar. Klik Tambah Pengajar untuk membuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal" id="pengajarModal">
    <div class="card">
        <h2 id="modalTitle">Tambah Pengajar</h2>
        <form id="pengajarForm" method="POST">
            @csrf
            <div id="methodContainer"></div>

            <label for="f_nama">Nama Lengkap & Gelar</label>
            <input id="f_nama" name="nama" placeholder="Mis. Dr. Ahmad Fauzi, M.Kom." required>

            <label for="f_nidn">NIDN</label>
            <input id="f_nidn" name="nidn" placeholder="Mis. 0001018001">

            <label for="f_email">Email (Digunakan untuk Login)</label>
            <input id="f_email" name="email" type="email" placeholder="nama@unsri.ac.id" required>

            <label for="f_hp">No. HP (WhatsApp)</label>
            <input id="f_hp" name="hp" placeholder="08xxxxxxxxxx">

            <label for="f_bidang">Bidang Keahlian</label>
            <input id="f_bidang" name="bidang" placeholder="Mis. Pemrograman Web / Data Science">

            <p class="mu" style="font-size:12px;margin-top:8px">
                * Password akun login default pengajar baru adalah <b>pengajar123</b>.
            </p>

            <div class="row" style="margin-top:16px;justify-content:flex-end">
                <button type="button" onclick="closeModal()">Batal</button>
                <button type="submit" class="p">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('pengajarModal');
    const form = document.getElementById('pengajarForm');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        title.textContent = 'Tambah Pengajar';
        form.action = "{{ route('admin.pengajar.store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        modal.classList.add('open');
    }

    function openEditModal(p) {
        title.textContent = 'Edit Pengajar';
        form.action = `{{ url('admin/pengajar') }}/${p.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        document.getElementById('f_nama').value = p.nama || '';
        document.getElementById('f_nidn').value = p.nidn || '';
        document.getElementById('f_email').value = p.email || '';
        document.getElementById('f_hp').value = p.hp || '';
        document.getElementById('f_bidang').value = p.bidang || '';

        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
    }
</script>
@endpush
@endsection
