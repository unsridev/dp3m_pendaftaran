<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Pengajar;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'menunggu' => Peserta::where('status', 'Menunggu Verifikasi')->count(),
            'terverifikasi' => Peserta::where('status', 'Terverifikasi')->count(),
            'ditolak' => Peserta::where('status', 'Ditolak')->count(),
            'kelas' => Kelas::count(),
            'pengajar' => Pengajar::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /* ---------------- VERIFIKASI ---------------- */
    public function verifikasi()
    {
        $pesertas = Peserta::where('status', 'Menunggu Verifikasi')->latest()->get();
        return view('admin.verifikasi', compact('pesertas'));
    }

    public function verifikasiAksi(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);
        $aksi = $request->input('aksi');

        if ($aksi === 'setuju') {
            $peserta->update([
                'status' => 'Terverifikasi',
                'alasan' => null,
            ]);
            return back()->with('success', "Peserta {$peserta->nama} berhasil disetujui.");
        } elseif ($aksi === 'tolak') {
            $alasan = $request->input('alasan', 'Tidak memenuhi syarat');
            $peserta->update([
                'status' => 'Ditolak',
                'alasan' => $alasan,
            ]);
            return back()->with('success', "Peserta {$peserta->nama} telah ditolak.");
        }

        return back();
    }

    /* ---------------- DATA PESERTA ---------------- */
    public function pesertaIndex(Request $request)
    {
        $query = Peserta::with('kelas');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('nidn', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pesertas = $query->latest()->get();
        $kelasList = Kelas::orderBy('nama')->get();

        return view('admin.peserta.index', compact('pesertas', 'kelasList'));
    }

    public function pesertaStore(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits:16',
            'nidn' => 'nullable|string',
            'nama' => 'required|string|max:255',
            'hp' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'tipe' => 'required|in:Dosen UNSRI,Dosen Luar',
            'status' => 'required|in:Menunggu Verifikasi,Terverifikasi,Ditolak',
            'kelas_id' => 'nullable|exists:kelas,id',
            'el_username' => 'nullable|string',
            'el_password' => 'nullable|string',
            'wa' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['nama'],
            'username' => $validated['email'],
            'email' => $validated['email'],
            'password' => Hash::make('peserta123'),
            'role' => 'peserta',
        ]);

        $validated['user_id'] = $user->id;
        Peserta::create($validated);

        return back()->with('success', 'Data peserta berhasil ditambahkan.');
    }

    public function pesertaUpdate(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $validated = $request->validate([
            'nik' => 'required|digits:16',
            'nidn' => 'nullable|string',
            'nama' => 'required|string|max:255',
            'hp' => 'required|string',
            'email' => 'required|email|unique:users,email,' . ($peserta->user_id ?? 0),
            'tipe' => 'required|in:Dosen UNSRI,Dosen Luar',
            'status' => 'required|in:Menunggu Verifikasi,Terverifikasi,Ditolak',
            'kelas_id' => 'nullable|exists:kelas,id',
            'el_username' => 'nullable|string',
            'el_password' => 'nullable|string',
            'wa' => 'nullable|string',
        ]);

        $peserta->update($validated);

        if ($peserta->user) {
            $peserta->user->update([
                'name' => $validated['nama'],
                'username' => $validated['email'],
                'email' => $validated['email'],
            ]);
        }

        return back()->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function pesertaDestroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        if ($peserta->user) {
            $peserta->user->delete();
        } else {
            $peserta->delete();
        }

        return back()->with('success', 'Data peserta berhasil dihapus.');
    }

    /* ---------------- DATA KELAS & PLOTTING ---------------- */
    public function kelasIndex()
    {
        $kelas = Kelas::withCount('pesertas')->orderBy('kode')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function kelasStore(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:kelas,kode|max:50',
            'nama' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'wa' => 'nullable|url|max:255',
        ]);

        Kelas::create($validated);
        return back()->with('success', 'Kelas berhasil dibuat.');
    }

    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:kelas,kode,' . $kelas->id,
            'nama' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'wa' => 'nullable|url|max:255',
        ]);

        $kelas->update($validated);
        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function kelasDestroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    public function kelasPlotting(Request $request, $id)
    {
        $kelas = Kelas::with(['pesertas' => function ($q) {
            $q->where('status', 'Terverifikasi');
        }])->findOrFail($id);

        $filter = $request->query('filter', 'belum'); // 'belum' or 'semua'

        $candidatesQuery = Peserta::where('status', 'Terverifikasi')
            ->where(function ($q) use ($id, $filter) {
                if ($filter === 'belum') {
                    $q->whereNull('kelas_id');
                } else {
                    $q->where('kelas_id', '!=', $id)->orWhereNull('kelas_id');
                }
            });

        $candidates = $candidatesQuery->with('kelas')->orderBy('nama')->get();

        return view('admin.kelas.plot', compact('kelas', 'candidates', 'filter'));
    }

    public function kelasPlottingAdd(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu peserta.');
        }

        $currentCount = $kelas->pesertas()->where('status', 'Terverifikasi')->count();
        if ($currentCount + count($ids) > $kelas->kuota && !$request->boolean('force')) {
            return back()->with('error', "Jumlah peserta melebihi kuota kelas ({$kelas->kuota}).");
        }

        Peserta::whereIn('id', $ids)->update(['kelas_id' => $kelas->id]);

        return back()->with('success', count($ids) . ' peserta berhasil dimasukkan ke kelas.');
    }

    public function kelasPlottingRemove(Request $request, $id)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu peserta untuk dikeluarkan.');
        }

        Peserta::whereIn('id', $ids)->where('kelas_id', $id)->update(['kelas_id' => null]);

        return back()->with('success', count($ids) . ' peserta berhasil dikeluarkan dari kelas.');
    }

    /* ---------------- JADWAL ---------------- */
    public function jadwalIndex()
    {
        $jadwals = Jadwal::with(['kelas', 'pengajar'])->orderBy('hari')->get();
        $kelasList = Kelas::orderBy('nama')->get();
        $pengajarList = Pengajar::orderBy('nama')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal.index', compact('jadwals', 'kelasList', 'pengajarList', 'hariList'));
    }

    public function jadwalStore(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'pengajar_id' => 'required|exists:pengajars,id',
            'hari' => 'required|string',
            'jam' => 'required|string',
            'ruang' => 'required|string',
        ]);

        Jadwal::create($validated);
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'pengajar_id' => 'required|exists:pengajars,id',
            'hari' => 'required|string',
            'jam' => 'required|string',
            'ruang' => 'required|string',
        ]);

        $jadwal->update($validated);
        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function jadwalDestroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /* ---------------- DATA PENGAJAR ---------------- */
    public function pengajarIndex()
    {
        $pengajars = Pengajar::orderBy('nama')->get();
        return view('admin.pengajar.index', compact('pengajars'));
    }

    public function pengajarStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'hp' => 'nullable|string',
            'bidang' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['nama'],
            'username' => $validated['email'],
            'email' => $validated['email'],
            'password' => Hash::make('pengajar123'),
            'role' => 'pengajar',
        ]);

        $validated['user_id'] = $user->id;
        Pengajar::create($validated);

        return back()->with('success', 'Data pengajar berhasil ditambahkan.');
    }

    public function pengajarUpdate(Request $request, $id)
    {
        $pengajar = Pengajar::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . ($pengajar->user_id ?? 0),
            'hp' => 'nullable|string',
            'bidang' => 'nullable|string',
        ]);

        $pengajar->update($validated);

        if ($pengajar->user) {
            $pengajar->user->update([
                'name' => $validated['nama'],
                'username' => $validated['email'],
                'email' => $validated['email'],
            ]);
        }

        return back()->with('success', 'Data pengajar berhasil diperbarui.');
    }

    public function pengajarDestroy($id)
    {
        $pengajar = Pengajar::findOrFail($id);
        if ($pengajar->user) {
            $pengajar->user->delete();
        } else {
            $pengajar->delete();
        }

        return back()->with('success', 'Data pengajar berhasil dihapus.');
    }

    /* ---------------- DATABASE DOSEN MASTER ---------------- */
    public function dosenIndex(Request $request)
    {
        $query = Dosen::query();
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                    ->orWhere('nidn', 'like', "%{$q}%")
                    ->orWhere('instansi', 'like', "%{$q}%");
            });
        }
        $dosens = $query->orderBy('nama')->get();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function dosenStore(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|string|unique:dosens,nidn|max:50',
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
        ]);

        Dosen::create($validated);
        return back()->with('success', 'Data dosen master berhasil ditambahkan.');
    }

    public function dosenUpdate(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validated = $request->validate([
            'nidn' => 'required|string|max:50|unique:dosens,nidn,' . $dosen->id,
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
        ]);

        $dosen->update($validated);
        return back()->with('success', 'Data dosen master berhasil diperbarui.');
    }

    public function dosenDestroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();
        return back()->with('success', 'Data dosen master berhasil dihapus.');
    }
}
