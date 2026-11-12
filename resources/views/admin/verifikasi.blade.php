@extends('layouts.app')

@section('title', 'Verifikasi Peserta - Pelatihan UNSRI')

@section('content')
<h1>Verifikasi Peserta</h1>
<p class="mu">Peserta baru bisa login setelah disetujui oleh admin.</p>

@forelse ($pesertas as $p)
    <div class="card">
        <div class="row">
            <b style="font-size:16px">{{ $p->nama }}</b>
            <span class="b wr">{{ $p->status }}</span>
            <span class="b {{ $p->tipe === 'Dosen UNSRI' ? 'ok' : 'wr' }}">{{ $p->tipe }}</span>
        </div>

        <div class="mu" style="margin-top:6px">
            NIK {{ $p->nik }} · NIDN/NUPTK {{ $p->nidn ?: '-' }} · {{ $p->hp }} · {{ $p->email }}
        </div>

        @if ($p->tipe === 'Dosen Luar')
            <div style="margin-top:12px">
                <p style="font-weight:500;margin-bottom:6px">Bukti Pembayaran:</p>
                @if ($p->bukti)
                    @php
                        $ext = strtolower(pathinfo($p->bukti, PATHINFO_EXTENSION));
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                    @endphp
                    @if ($isImg)
                        <a href="{{ asset('storage/' . $p->bukti) }}" target="_blank">
                            <img class="bk" src="{{ asset('storage/' . $p->bukti) }}" alt="Bukti {{ $p->nama }}">
                        </a>
                    @else
                        <a href="{{ asset('storage/' . $p->bukti) }}" target="_blank" download="bukti-{{ $p->id }}.pdf">
                            <button type="button" class="s">📄 Unduh file bukti (PDF)</button>
                        </a>
                    @endif
                @else
                    <span class="b er">Belum diunggah</span>
                @endif
            </div>
        @endif

        <div class="row" style="margin-top:14px">
            <form action="{{ route('admin.verifikasi.aksi', $p->id) }}" method="POST" style="margin:0">
                @csrf
                <input type="hidden" name="aksi" value="setuju">
                <button type="submit" class="p" onclick="return confirm('Setujui pendaftaran ini?')">Setujui</button>
            </form>

            <button type="button" class="d" onclick="openRejectModal('{{ $p->id }}', '{{ addslashes($p->nama) }}')">Tolak</button>
        </div>
    </div>
@empty
    <div class="card mu" style="padding:24px;text-align:center">
        Tidak ada pendaftar yang menunggu verifikasi saat ini.
    </div>
@endforelse

<!-- Modal Tolak -->
<div class="modal" id="rejectModal">
    <div class="card">
        <h2>Tolak Pendaftaran</h2>
        <p class="mu" id="rejectName"></p>
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="aksi" value="tolak">
            <label for="alasan">Alasan Penolakan</label>
            <textarea id="alasan" name="alasan" rows="3" required placeholder="Mis. Bukti pembayaran tidak terbaca atau NIK tidak valid..."></textarea>

            <div class="row" style="margin-top:16px;justify-content:flex-end">
                <button type="button" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="p d">Tolak Pendaftaran</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(id, name) {
        const form = document.getElementById('rejectForm');
        form.action = `{{ url('admin/verifikasi') }}/${id}`;
        document.getElementById('rejectName').textContent = `Peserta: ${name}`;
        document.getElementById('rejectModal').classList.add('open');
        document.getElementById('alasan').focus();
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('open');
    }
</script>
@endpush
@endsection
