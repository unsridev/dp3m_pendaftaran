<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($credentials['username']);

        // Check user by username or email
        $user = User::where('username', $input)
            ->orWhere('email', $input)
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withInput()->with('error', 'Username atau password salah.');
        }

        // Role-specific validation
        if ($user->role === 'peserta') {
            $peserta = $user->peserta;
            if ($peserta) {
                if ($peserta->status === 'Menunggu Verifikasi') {
                    return back()->withInput()->with('error', 'Akun Anda belum diverifikasi admin. Silakan coba lagi setelah diverifikasi.');
                }
                if ($peserta->status === 'Ditolak') {
                    $alasan = $peserta->alasan ? ': ' . $peserta->alasan : '.';
                    return back()->withInput()->with('error', 'Pendaftaran ditolak' . $alasan . ' Hubungi admin.');
                }
            }
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function lookupDosen(Request $request)
    {
        $nidn = trim($request->query('nidn', ''));
        if (empty($nidn)) {
            return response()->json(['found' => false]);
        }

        $dosen = Dosen::where('nidn', $nidn)->first();
        if ($dosen) {
            return response()->json([
                'found' => true,
                'nama' => $dosen->nama,
                'instansi' => $dosen->instansi,
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nidn' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'hp' => ['required', 'regex:/^(08|\+628|628)\d{7,12}$/'],
            'email' => 'required|email|max:255|unique:users,email',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1536',
        ], [
            'nik.digits' => 'NIK harus 16 digit angka.',
            'hp.regex' => 'Nomor HP harus diawali 08 atau +62.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'bukti.max' => 'Ukuran file bukti pembayaran maksimal 1,5 MB.',
        ]);

        $nidn = trim($request->input('nidn', ''));
        $dosen = !empty($nidn) ? Dosen::where('nidn', $nidn)->first() : null;

        if ($dosen) {
            $tipe = 'Dosen UNSRI';
            $nama = $dosen->nama;
            $buktiPath = null;
        } else {
            $tipe = 'Dosen Luar';
            $nama = $request->input('nama');
            if (!$request->hasFile('bukti')) {
                return back()->withInput()->with('error', 'Dosen luar wajib mengunggah bukti pembayaran.');
            }
            $file = $request->file('bukti');
            $buktiPath = $file->store('bukti', 'public');
        }

        // Create User
        $user = User::create([
            'name' => $nama,
            'username' => $request->input('email'),
            'email' => $request->input('email'),
            'password' => Hash::make('peserta123'),
            'role' => 'peserta',
        ]);

        // Create Peserta
        Peserta::create([
            'user_id' => $user->id,
            'nik' => $request->input('nik'),
            'nidn' => $nidn ?: null,
            'nama' => $nama,
            'hp' => $request->input('hp'),
            'email' => $request->input('email'),
            'tipe' => $tipe,
            'status' => 'Menunggu Verifikasi',
            'bukti' => $buktiPath,
        ]);

        return view('auth.register_success');
    }

    public function resetDemo()
    {
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        return redirect()->route('login')->with('success', 'Data demo berhasil di-reset ke kondisi awal!');
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'pengajar') {
            return redirect()->route('pengajar.jadwal');
        }
        return redirect()->route('peserta.dashboard');
    }
}
