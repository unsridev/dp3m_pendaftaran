<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Pengajar;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Dosen Master Data
        $dosenList = [
            ['0001018001', 'Dr. Ahmad Fauzi, M.Kom.', 'UNSRI - Fakultas Ilmu Komputer'],
            ['0002028002', 'Prof. Siti Rahmawati, M.Si.', 'UNSRI - Fakultas MIPA'],
            ['0003038003', 'Dr. Budi Santoso, S.T., M.T.', 'UNSRI - Fakultas Teknik'],
            ['0004048004', 'Dewi Lestari, S.E., M.M.', 'UNSRI - Fakultas Ekonomi'],
            ['0005058005', 'Dr. Hendra Wijaya, S.H., M.H.', 'UNSRI - Fakultas Hukum'],
            ['0006068006', 'Rina Marlina, S.Pd., M.Pd.', 'UNSRI - FKIP'],
            ['0007078007', 'Dr. Joko Prasetyo, M.Kom.', 'UNSRI - Fakultas Ilmu Komputer'],
            ['0008088008', 'Dr. Maya Anggraini, M.Sc.', 'UNSRI - Fakultas MIPA'],
            ['0009098009', 'Eko Nugroho, S.T., M.Eng.', 'UNSRI - Fakultas Teknik'],
            ['0010108010', 'Dr. Fitri Handayani, M.Pd.', 'UNSRI - FKIP'],
            ['0011118011', 'Agus Salim, S.Kom., M.T.I.', 'UNSRI - Fakultas Ilmu Komputer'],
            ['0012128012', 'Dr. Linda Permata, M.Kes.', 'UNSRI - Fakultas Kedokteran'],
            ['0013138013', 'Rudi Hartono, S.P., M.Si.', 'UNSRI - Fakultas Pertanian'],
            ['0014148014', 'Dr. Nur Aini, S.Kom., M.Cs.', 'UNSRI - Fakultas Ilmu Komputer'],
            ['0015158015', 'Taufik Hidayat, S.T., M.T.', 'UNSRI - Fakultas Teknik'],
        ];

        foreach ($dosenList as $d) {
            Dosen::create([
                'nidn' => $d[0],
                'nama' => $d[1],
                'instansi' => $d[2],
            ]);
        }

        // 2. Admin User
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@unsri.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 3. Pengajar & Users
        $pengajarsData = [
            [
                'nama' => 'Dr. Ahmad Fauzi, M.Kom.',
                'nidn' => '0001018001',
                'email' => 'ahmad@unsri.ac.id',
                'hp' => '081234560001',
                'bidang' => 'Pemrograman Web',
            ],
            [
                'nama' => 'Dr. Nur Aini, S.Kom., M.Cs.',
                'nidn' => '0014148014',
                'email' => 'nur@unsri.ac.id',
                'hp' => '081234560002',
                'bidang' => 'Data Science',
            ],
            [
                'nama' => 'Agus Salim, S.Kom., M.T.I.',
                'nidn' => '0011118011',
                'email' => 'agus@unsri.ac.id',
                'hp' => '081234560003',
                'bidang' => 'Keamanan Informasi',
            ],
        ];

        $pengajarModels = [];
        foreach ($pengajarsData as $pData) {
            $user = User::create([
                'name' => $pData['nama'],
                'username' => $pData['email'],
                'email' => $pData['email'],
                'password' => Hash::make('pengajar123'),
                'role' => 'pengajar',
            ]);

            $pModel = Pengajar::create([
                'user_id' => $user->id,
                'nama' => $pData['nama'],
                'nidn' => $pData['nidn'],
                'email' => $pData['email'],
                'hp' => $pData['hp'],
                'bidang' => $pData['bidang'],
            ]);
            $pengajarModels[$pData['email']] = $pModel;
        }

        // 4. Kelas
        $k1 = Kelas::create([
            'kode' => 'WEB-01',
            'nama' => 'Pengembangan Aplikasi Web',
            'kuota' => 30,
            'wa' => 'https://chat.whatsapp.com/contoh-web01',
        ]);

        $k2 = Kelas::create([
            'kode' => 'DAT-01',
            'nama' => 'Analisis Data untuk Dosen',
            'kuota' => 25,
            'wa' => null,
        ]);

        $k3 = Kelas::create([
            'kode' => 'SEC-01',
            'nama' => 'Keamanan Sistem Informasi',
            'kuota' => 20,
            'wa' => null,
        ]);

        // 5. Jadwal
        Jadwal::create([
            'kelas_id' => $k1->id,
            'pengajar_id' => $pengajarModels['ahmad@unsri.ac.id']->id,
            'hari' => 'Senin',
            'jam' => '09:00-11:00',
            'ruang' => 'Lab Komputer 1',
        ]);
        Jadwal::create([
            'kelas_id' => $k1->id,
            'pengajar_id' => $pengajarModels['ahmad@unsri.ac.id']->id,
            'hari' => 'Rabu',
            'jam' => '09:00-11:00',
            'ruang' => 'Lab Komputer 1',
        ]);
        Jadwal::create([
            'kelas_id' => $k2->id,
            'pengajar_id' => $pengajarModels['nur@unsri.ac.id']->id,
            'hari' => 'Selasa',
            'jam' => '13:00-15:00',
            'ruang' => 'Ruang Seminar',
        ]);
        Jadwal::create([
            'kelas_id' => $k3->id,
            'pengajar_id' => $pengajarModels['agus@unsri.ac.id']->id,
            'hari' => 'Kamis',
            'jam' => '10:00-12:00',
            'ruang' => 'Lab Jaringan',
        ]);

        // 6. Peserta & Users
        $pesertaData = [
            [
                'nik' => '1671010101900001',
                'nidn' => '0002028002',
                'nama' => 'Prof. Siti Rahmawati, M.Si.',
                'hp' => '081311110001',
                'email' => 'siti@unsri.ac.id',
                'tipe' => 'Dosen UNSRI',
                'status' => 'Terverifikasi',
                'kelas_id' => $k1->id,
                'el_username' => 'siti.r',
                'el_password' => 'elearn123',
                'alasan' => null,
            ],
            [
                'nik' => '1671010202900002',
                'nidn' => '0003038003',
                'nama' => 'Dr. Budi Santoso, S.T., M.T.',
                'hp' => '081311110002',
                'email' => 'budi@unsri.ac.id',
                'tipe' => 'Dosen UNSRI',
                'status' => 'Menunggu Verifikasi',
                'kelas_id' => null,
                'el_username' => null,
                'el_password' => null,
                'alasan' => null,
            ],
            [
                'nik' => '1671010303900003',
                'nidn' => '9900112233',
                'nama' => 'Joko Widodo, M.Pd.',
                'hp' => '081311110003',
                'email' => 'joko@univluar.ac.id',
                'tipe' => 'Dosen Luar',
                'status' => 'Menunggu Verifikasi',
                'kelas_id' => null,
                'el_username' => null,
                'el_password' => null,
                'alasan' => null,
            ],
            [
                'nik' => '1671010404900004',
                'nidn' => null,
                'nama' => 'Rina Kusuma, M.Si.',
                'hp' => '081311110004',
                'email' => 'rina@univx.ac.id',
                'tipe' => 'Dosen Luar',
                'status' => 'Ditolak',
                'kelas_id' => null,
                'el_username' => null,
                'el_password' => null,
                'alasan' => 'Bukti pembayaran tidak terbaca',
            ],
        ];

        foreach ($pesertaData as $p) {
            $user = User::create([
                'name' => $p['nama'],
                'username' => $p['email'],
                'email' => $p['email'],
                'password' => Hash::make('peserta123'),
                'role' => 'peserta',
            ]);

            Peserta::create([
                'user_id' => $user->id,
                'kelas_id' => $p['kelas_id'],
                'nik' => $p['nik'],
                'nidn' => $p['nidn'],
                'nama' => $p['nama'],
                'hp' => $p['hp'],
                'email' => $p['email'],
                'tipe' => $p['tipe'],
                'status' => $p['status'],
                'bukti' => null,
                'alasan' => $p['alasan'],
                'el_username' => $p['el_username'],
                'el_password' => $p['el_password'],
                'wa' => null,
            ]);
        }
    }
}
