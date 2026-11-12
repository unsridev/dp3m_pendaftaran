<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pendaftaran Pelatihan UNSRI')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#ffffff;--card:#ffffff;--tx:#0f172a;--mu:#64748b;--bd:#e2e8f0;
            --pr:#2563eb;--pr-hover:#1d4ed8;--prt:#ffffff;--sb:#1e3a8a;--lb:#eff6ff;--lbt:#1d4ed8;
            --ok:#16a34a;--wr:#d97706;--er:#dc2626;--okb:#f0fdf4;--wrb:#fffbeb;--erb:#fef2f2;
            box-sizing:border-box;
            padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px);
        }
        *{box-sizing:border-box}
        html,body{min-height:100%}
        body{
            margin:0;
            font-family:Inter,system-ui,-apple-system,'Segoe UI',sans-serif;
            background-color:#ffffff;
            color:var(--tx);
            font-size:14px;
        }
        a{color:var(--pr);text-decoration:none;font-weight:500}
        a:hover{color:var(--pr-hover);text-decoration:underline}
        h1{font-size:26px;font-weight:700;margin:0 0 4px;color:#0f172a}
        h2{font-size:16px;margin:0 0 12px;color:#1e3a8a}
        .mu{color:var(--mu)}
        .app{display:flex;min-height:100vh}
        .sb{
            width:260px;
            background:linear-gradient(180deg,#1e3a8a 0%,#1d4ed8 100%);
            color:#eff6ff;
            padding:20px 14px;
            flex-shrink:0;
            display:flex;
            flex-direction:column;
            gap:3px;
            position:sticky;
            top:0;
            height:100vh;
            overflow-y:auto;
            box-shadow:2px 0 12px rgba(30,58,138,0.08);
        }
        .sb .logo{
            display:flex;gap:12px;align-items:center;font-weight:700;font-size:16px;
            padding:4px 10px 18px;margin-bottom:12px;border-bottom:1px solid rgba(255,255,255,.18);color:#fff;
        }
        .logo .bg{background:#ffffff;color:#1e3a8a;border-radius:10px;font-weight:800;padding:8px 9px;font-size:15px;box-shadow:0 2px 6px rgba(0,0,0,0.15)}
        .logo small{display:block;font-weight:400;font-size:11px;opacity:.85;color:#dbeafe}
        .sb a{
            padding:10px 14px;border-radius:8px;color:#e0e7ff;text-decoration:none;
            cursor:pointer;display:flex;align-items:center;gap:10px;font-weight:500;
            transition:all .15s ease-in-out;
        }
        .sb a:hover{background:rgba(255,255,255,.15);color:#fff}
        .sb a.on{background:#ffffff;color:#1e3a8a;font-weight:700;box-shadow:0 2px 8px rgba(0,0,0,0.12)}
        .main{flex:1;min-width:0;background-color:#ffffff}
        .hd{display:flex;align-items:center;gap:12px;background:#ffffff;border-bottom:1px solid #e2e8f0;padding:14px 28px}
        .hd>div:nth-child(2){flex:1}
        .lo{
            background:var(--erb);color:var(--er);padding:8px 16px;border-radius:8px;
            font-weight:600;text-decoration:none;border:1px solid #fecaca;cursor:pointer;
            transition:all .15s ease-in-out;
        }
        .lo:hover{background:var(--er);color:#fff;border-color:var(--er)}
        .ct{padding:26px 28px;background-color:#ffffff}
        .top{display:none;align-items:center;gap:10px;margin-bottom:0}
        @media(max-width:760px){
            .sb{position:fixed;left:-260px;z-index:100;transition:left .2s;top:0}
            .sb.open{left:0}
            .top{display:flex}
            .ct{padding:16px}
            .hd{padding:10px 16px}
        }
        .card{
            background:#ffffff;
            border:1px solid #e2e8f0;
            border-radius:12px;
            padding:20px;
            margin-bottom:18px;
            box-shadow:0 2px 8px rgba(37,99,235,0.04);
        }
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:18px}
        .stat{
            background:#ffffff;
            border:1px solid #dbeafe;
            border-radius:12px;
            padding:16px;
            box-shadow:0 2px 6px rgba(37,99,235,0.04);
        }
        .stat b{display:block;font-size:28px;margin-bottom:2px}
        .tw{overflow-x:auto}
        table{width:100%;border-collapse:collapse}
        th,td{text-align:left;padding:11px 12px;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        th{color:#1e40af;font-weight:600;font-size:11px;text-transform:uppercase;background:#eff6ff;border-bottom:1px solid #dbeafe}
        input,select,textarea{
            width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;
            background:#ffffff;color:var(--tx);font:inherit;
            transition:border-color .15s,box-shadow .15s;
        }
        input:focus,select:focus,textarea:focus{
            outline:none;
            border-color:var(--pr);
            box-shadow:0 0 0 3px rgba(37,99,235,0.15);
        }
        label{display:block;font-weight:600;margin:12px 0 4px;color:#1e293b}
        button{
            font:inherit;padding:8px 16px;border-radius:8px;border:1px solid #cbd5e1;
            background:#ffffff;color:var(--tx);cursor:pointer;
            transition:all .15s ease-in-out;
        }
        button:hover{background:#f8fafc;border-color:#94a3b8}
        button.p{background:var(--pr);color:var(--prt);border-color:var(--pr);font-weight:600}
        button.p:hover{background:var(--pr-hover);border-color:var(--pr-hover)}
        button.d{color:var(--er);border-color:#fecaca}
        button.d:hover{background:#fef2f2}
        button.s{padding:5px 12px;font-size:12px;font-weight:600;background:var(--lb);color:var(--lbt);border-color:#bfdbfe}
        button.s:hover{background:#dbeafe}
        button.s.p{background:var(--pr);color:var(--prt);border-color:var(--pr)}
        button.s.p:hover{background:var(--pr-hover)}
        button.s.d{background:var(--erb);color:var(--er);border-color:#fecaca}
        button.s.d:hover{background:#fee2e2}
        .row{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:12px}
        .row>input,.row>select{width:auto;flex:1;min-width:140px}
        .b{display:inline-block;padding:3px 10px;border-radius:99px;font-size:12px;font-weight:600}
        .b.ok{background:var(--okb);color:var(--ok);border:1px solid #bbf7d0}
        .b.wr{background:var(--wrb);color:var(--wr);border:1px solid #fde68a}
        .b.er{background:var(--erb);color:var(--er);border:1px solid #fecaca}
        .modal{
            position:fixed;inset:0;background:rgba(15,23,42,.4);backdrop-filter:blur(3px);display:none;
            align-items:center;justify-content:center;z-index:150;padding:16px;
        }
        .modal.open{display:flex}
        .modal .card{width:100%;max-width:480px;max-height:90vh;overflow-y:auto;margin:0;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 10px 10px -5px rgba(0,0,0,0.04);border:1px solid #dbeafe}
        .toast{
            position:fixed;bottom:24px;left:50%;transform:translateX(-50%);
            background:var(--sb);color:#fff;padding:12px 24px;border-radius:10px;z-index:200;
            box-shadow:0 10px 25px rgba(30,58,138,0.25);font-weight:500;
        }
        .err{color:var(--er);font-size:13px;margin-top:8px;font-weight:500}
        img.bk{max-width:100%;max-height:220px;border-radius:8px;border:1px solid #cbd5e1}
    </style>
</head>
<body>
    @php
        $user = Auth::user();
        $role = $user->role;
        $displayName = $role === 'admin'
            ? 'Administrator'
            : ($role === 'pengajar' ? ($user->pengajar ? $user->pengajar->nama : $user->name) : ($user->peserta ? $user->peserta->nama : $user->name));
    @endphp

    <div class="app">
        <!-- Sidebar -->
        <nav class="sb" id="sb">
            <div class="logo">
                <span class="bg">US</span>
                <div>
                    Pelatihan UNSRI
                    <small>Sistem Pendaftaran Pelatihan</small>
                </div>
            </div>

            @if($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
                <a href="{{ route('admin.verifikasi') }}" class="{{ request()->routeIs('admin.verifikasi') ? 'on' : '' }}">Verifikasi Peserta</a>
                <a href="{{ route('admin.peserta.index') }}" class="{{ request()->routeIs('admin.peserta.*') ? 'on' : '' }}">Data Peserta</a>
                <a href="{{ route('admin.kelas.index') }}" class="{{ request()->routeIs('admin.kelas.*') ? 'on' : '' }}">Data Kelas</a>
                <a href="{{ route('admin.jadwal.index') }}" class="{{ request()->routeIs('admin.jadwal.*') ? 'on' : '' }}">Jadwal</a>
                <a href="{{ route('admin.pengajar.index') }}" class="{{ request()->routeIs('admin.pengajar.*') ? 'on' : '' }}">Data Pengajar</a>
                <a href="{{ route('admin.dosen.index') }}" class="{{ request()->routeIs('admin.dosen.*') ? 'on' : '' }}">Database Dosen</a>
            @elseif($role === 'peserta')
                <a href="{{ route('peserta.dashboard') }}" class="{{ request()->routeIs('peserta.dashboard') ? 'on' : '' }}">Dashboard</a>
                <a href="{{ route('peserta.jadwal') }}" class="{{ request()->routeIs('peserta.jadwal') ? 'on' : '' }}">Jadwal</a>
                <a href="{{ route('peserta.elearning') }}" class="{{ request()->routeIs('peserta.elearning') ? 'on' : '' }}">Akun E-Learning</a>
                <a href="{{ route('peserta.wa') }}" class="{{ request()->routeIs('peserta.wa') ? 'on' : '' }}">Grup WhatsApp</a>
                <a href="{{ route('peserta.profil') }}" class="{{ request()->routeIs('peserta.profil') ? 'on' : '' }}">Profil</a>
            @elseif($role === 'pengajar')
                <a href="{{ route('pengajar.jadwal') }}" class="{{ request()->routeIs('pengajar.jadwal') ? 'on' : '' }}">Jadwal Mengajar</a>
                <a href="{{ route('pengajar.peserta') }}" class="{{ request()->routeIs('pengajar.peserta') ? 'on' : '' }}">Peserta Kelas</a>
            @endif
        </nav>

        <!-- Main Content -->
        <main class="main">
            <header class="hd">
                <div class="top">
                    <button type="button" onclick="document.getElementById('sb').classList.toggle('open')" aria-label="Menu">☰</button>
                </div>
                <div>
                    <b>{{ $displayName }}</b><br>
                    <span class="mu" style="text-transform: capitalize">{{ $role }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="lo">Logout</button>
                </form>
            </header>

            <div class="ct">
                @yield('content')
            </div>
        </main>
    </div>

    @if (session('success'))
        <div class="toast" id="flash-toast">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="toast" style="background:var(--er);" id="flash-toast">{{ session('error') }}</div>
    @endif

    <script>
        function showToast(msg, isErr=false) {
            const t = document.createElement('div');
            t.className = 'toast';
            if (isErr) t.style.background = 'var(--er)';
            t.textContent = msg;
            document.body.append(t);
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transition = 'opacity 0.4s';
                setTimeout(() => t.remove(), 400);
            }, 2500);
        }

        const t = document.getElementById('flash-toast');
        if (t) {
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transition = 'opacity 0.4s';
                setTimeout(() => t.remove(), 400);
            }, 3000);
        }

        function copyText(txt) {
            navigator.clipboard.writeText(txt).then(() => {
                showToast('Tersalin');
            }).catch(() => {
                showToast('Gagal menyalin', true);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
