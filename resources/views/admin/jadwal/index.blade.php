@extends('layouts.app')

@section('title', 'Jadwal Perkuliahan - Pelatihan UNSRI')

@section('content')
<h1>Jadwal Perkuliahan</h1>

<div class="card">
    <div class="row">
        <button type="button" class="p" onclick="openCreateModal()">Tambah Jadwal</button>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Pengajar</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Ruang</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwals as $j)
                    <tr>
                        <td><b>{{ $j->kelas ? $j->kelas->nama : '-' }}</b></td>
                        <td>{{ $j->pengajar ? $j->pengajar->nama : '-' }}</td>
                        <td>{{ $j->hari }}</td>
                        <td>{{ $j->jam }}</td>
                        <td>{{ $j->ruang }}</td>
                        <td style="white-space:nowrap;text-align:right">
                            <button type="button" class="s" onclick='openEditModal(@json($j))'>Edit</button>
                            <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="s d">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="mu" style="text-align:center;padding:20px">
                            Belum ada jadwal perkuliahan. Klik Tambah Jadwal untuk membuat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal" id="jadwalModal">
    <div class="card">
        <h2 id="modalTitle">Tambah Jadwal</h2>
        <form id="jadwalForm" method="POST">
            @csrf
            <div id="methodContainer"></div>

            <label for="f_kelas_id">Kelas</label>
            <select id="f_kelas_id" name="kelas_id" required>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->kode }})</option>
                @endforeach
            </select>

            <label for="f_pengajar_id">Pengajar</label>
            <select id="f_pengajar_id" name="pengajar_id" required>
                @foreach ($pengajarList as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->bidang ?: 'Umum' }})</option>
                @endforeach
            </select>

            <label for="f_hari">Hari</label>
            <select id="f_hari" name="hari" required>
                @foreach ($hariList as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                @endforeach
            </select>

            <label for="f_jam">Jam Pelaksanaan</label>
            <input id="f_jam" name="jam" placeholder="Mis. 09:00-11:00" required>

            <label for="f_ruang">Ruang / Tempat</label>
            <input id="f_ruang" name="ruang" placeholder="Mis. Lab Komputer 1 / Zoom Room A" required>

            <div class="row" style="margin-top:16px;justify-content:flex-end">
                <button type="button" onclick="closeModal()">Batal</button>
                <button type="submit" class="p">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('jadwalModal');
    const form = document.getElementById('jadwalForm');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        title.textContent = 'Tambah Jadwal';
        form.action = "{{ route('admin.jadwal.store') }}";
        methodContainer.innerHTML = '';
        form.reset();
        modal.classList.add('open');
    }

    function openEditModal(j) {
        title.textContent = 'Edit Jadwal';
        form.action = `{{ url('admin/jadwal') }}/${j.id}`;
        methodContainer.innerHTML = '@method("PUT")';

        document.getElementById('f_kelas_id').value = j.kelas_id;
        document.getElementById('f_pengajar_id').value = j.pengajar_id;
        document.getElementById('f_hari').value = j.hari;
        document.getElementById('f_jam').value = j.jam;
        document.getElementById('f_ruang').value = j.ruang;

        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
    }
</script>
@endpush
@endsection
