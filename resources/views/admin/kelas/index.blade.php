@extends('layouts.app')

@section('title', 'Data Kelas - Pelatihan UNSRI')

@section('content')
<h1>Data Kelas</h1>

<div class="card">
    <div class="row">
        <button type="button" class="p" onclick="openCreateModal()">Tambah Kelas</button>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kelas</th>
                    <th>Kuota</th>
                    <th>Peserta</th>
                    <th>Grup WA</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelas as $k)
                    <tr>
                        <td><b>{{ $k->kode }}</b></td>
                        <td>{{ $k->nama }}</td>
                        <td>{{ $k->kuota }}</td>
                        <td>
                            <span class="b {{ $k->pesertas_count >= $k->kuota ? 'wr' : 'ok' }}">
                                {{ $k->pesertas_count }} / {{ $k->kuota }}
                            </span>
                        </td>
                        <td>
                            @if ($k->wa)
                                <a href="{{ $k->wa }}" target="_blank" rel="noopener">Link Aktif</a>
                            @else
                                <span class="mu">Belum diisi</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;text-align:right">
                            <a href="{{ route('admin.kelas.plotting', $k->id) }}">
                                <button type="button" class="s p">Peserta</button>
                            </a>
                            <button type="button" class="s" onclick='openEditModal(@json($k))'>Edit</button>
                            <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus kelas ini? Semua jadwal terkait juga akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="s d">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="mu" style="text-align:center;padding:20px">
                            Belum ada data kelas. Klik Tambah Kelas untuk membuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal" id="kelasModal">
    <div class="card">
        <h2 id="modalTitle">Tambah Kelas</h2>
        <form id="kelasForm" method="POST">
            @csrf
            <div id="methodContainer"></div>

            <label for="f_kode">Kode Kelas</label>
            <input id="f_kode" name="kode" placeholder="Mis. WEB-01" required>

            <label for="f_nama">Nama Kelas</label>
            <input id="f_nama" name="nama" placeholder="Mis. Pengembangan Aplikasi Web" required>

            <label for="f_kuota">Kuota Peserta</label>
            <input id="f_kuota" name="kuota" type="number" min="1" value="30" required>

            <label for="f_wa">Link Grup WhatsApp (opsional)</label>
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
    const modal = document.getElementById('kelasModal');
    const form = document.getElementById('kelasForm');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        title.textContent = 'Tambah Kelas';
        form.action = "{{ route('admin.kelas.store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        document.getElementById('f_kuota').value = '30';
        modal.classList.add('open');
    }

    function openEditModal(k) {
        title.textContent = 'Edit Kelas';
        form.action = `{{ url('admin/kelas') }}/${k.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        document.getElementById('f_kode').value = k.kode || '';
        document.getElementById('f_nama').value = k.nama || '';
        document.getElementById('f_kuota').value = k.kuota || 30;
        document.getElementById('f_wa').value = k.wa || '';

        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
    }
</script>
@endpush
@endsection
