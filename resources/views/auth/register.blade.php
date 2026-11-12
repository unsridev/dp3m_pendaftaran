@extends('layouts.auth')

@section('title', 'Pendaftaran Peserta - Pelatihan UNSRI')

@section('content')
<div class="auth">
    <div class="card">
        <h1>Pendaftaran</h1>
        <p class="mu">Isi data diri untuk mendaftar pelatihan.</p>

        <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" id="registerForm">
            @csrf

            <label for="nik">NIK</label>
            <input id="nik" name="nik" inputmode="numeric" maxlength="16" placeholder="16 digit" value="{{ old('nik') }}" required>

            <label for="nidn">NIDN / NUPTK</label>
            <input id="nidn" name="nidn" placeholder="Mis. 0001018001" value="{{ old('nidn') }}" oninput="lookupDosen()">
            <div id="dosen_info" style="margin-top:6px"></div>

            <label for="nama">Nama lengkap</label>
            <input id="nama" name="nama" value="{{ old('nama') }}" required>

            <label for="hp">Nomor HP (WhatsApp)</label>
            <input id="hp" name="hp" type="tel" placeholder="08xxxxxxxxxx" value="{{ old('hp') }}" required>

            <label for="email">Email instansi</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <div id="bukti_container" style="display:none">
                <label for="bukti">Bukti pembayaran (gambar/PDF, maks 1,5 MB)</label>
                <input id="bukti" name="bukti" type="file" accept="image/*,application/pdf">
            </div>

            @if ($errors->any())
                <div class="err">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="row" style="margin-top:16px">
                <button type="submit" class="p">Kirim pendaftaran</button>
                <a href="{{ route('login') }}" style="margin-left:auto">Kembali ke login</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let lookupTimer = null;

    function lookupDosen() {
        clearTimeout(lookupTimer);
        const nidnInput = document.getElementById('nidn');
        const namaInput = document.getElementById('nama');
        const infoDiv = document.getElementById('dosen_info');
        const buktiContainer = document.getElementById('bukti_container');
        const nidn = nidnInput.value.trim();

        if (!nidn) {
            infoDiv.innerHTML = '';
            namaInput.readOnly = false;
            buktiContainer.style.display = 'block';
            return;
        }

        lookupTimer = setTimeout(() => {
            fetch(`{{ route('dosen.lookup') }}?nidn=${encodeURIComponent(nidn)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.found) {
                        namaInput.value = data.nama;
                        namaInput.readOnly = true;
                        infoDiv.innerHTML = `<span class="b ok">Dosen UNSRI · ${data.instansi}</span>`;
                        buktiContainer.style.display = 'none';
                    } else {
                        if (namaInput.readOnly) {
                            namaInput.value = '';
                        }
                        namaInput.readOnly = false;
                        infoDiv.innerHTML = `<span class="b wr">Dosen luar · isi nama manual dan wajib unggah bukti pembayaran</span>`;
                        buktiContainer.style.display = 'block';
                    }
                })
                .catch(() => {
                    infoDiv.innerHTML = '';
                    namaInput.readOnly = false;
                    buktiContainer.style.display = 'block';
                });
        }, 300);
    }

    // Trigger initial check if old input present
    document.addEventListener('DOMContentLoaded', () => {
        lookupDosen();
    });
</script>
@endpush
@endsection
