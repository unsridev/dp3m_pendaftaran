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
        .card{
            background:var(--card);
            border:1px solid #e2e8f0;
            border-radius:12px;
            padding:20px;
            margin-bottom:16px;
            box-shadow:0 4px 16px rgba(37,99,235,0.06);
        }
        .auth{max-width:446px;margin:40px auto;padding:0 16px}
        .auth .card{padding:32px;border-radius:16px;box-shadow:0 10px 25px -5px rgba(37,99,235,0.1), 0 8px 10px -6px rgba(37,99,235,0.05);border:1px solid #dbeafe}
        .row{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:12px}
        .row>input,.row>select{width:auto;flex:1;min-width:140px}
        input,select,textarea{
            width:100%;
            padding:10px 12px;
            border:1px solid #cbd5e1;
            border-radius:8px;
            background:#ffffff;
            color:var(--tx);
            font:inherit;
            transition:border-color .15s,box-shadow .15s;
        }
        input:focus,select:focus,textarea:focus{
            outline:none;
            border-color:var(--pr);
            box-shadow:0 0 0 3px rgba(37,99,235,0.15);
        }
        input[readonly]{opacity:.85;background:#f8fafc}
        label{display:block;font-weight:600;margin:12px 0 4px;color:#1e293b}
        button{
            font:inherit;
            padding:9px 16px;
            border-radius:8px;
            border:1px solid #cbd5e1;
            background:#ffffff;
            color:var(--tx);
            cursor:pointer;
            transition:all .15s ease-in-out;
        }
        button:hover{background:#f8fafc;border-color:#94a3b8}
        button.p{background:var(--pr);color:var(--prt);border-color:var(--pr);font-weight:600}
        button.p:hover{background:var(--pr-hover);border-color:var(--pr-hover)}
        button.d{color:var(--er);border-color:#fecaca}
        button.d:hover{background:#fef2f2}
        button.s{padding:5px 12px;font-size:12px;font-weight:600;background:var(--lb);color:var(--lbt);border-color:#bfdbfe}
        button.s:hover{background:#dbeafe}
        button.s.d{background:var(--erb);color:var(--er);border-color:#fecaca}
        button.s.d:hover{background:#fee2e2}
        .b{display:inline-block;padding:3px 10px;border-radius:99px;font-size:12px;font-weight:600}
        .b.ok{background:var(--okb);color:var(--ok);border:1px solid #bbf7d0}
        .b.wr{background:var(--wrb);color:var(--wr);border:1px solid #fde68a}
        .b.er{background:var(--erb);color:var(--er);border:1px solid #fecaca}
        .toast{
            position:fixed;
            bottom:20px;
            left:50%;
            transform:translateX(-50%);
            background:var(--sb);
            color:#fff;
            padding:12px 22px;
            border-radius:10px;
            z-index:60;
            box-shadow:0 10px 25px rgba(30,58,138,0.25);
            font-weight:500;
        }
        .err{color:var(--er);font-size:13px;margin-top:8px;font-weight:500}
        .succ{color:var(--ok);font-size:13px;margin-top:8px;font-weight:500}
        .demo{background:#f8faff;border-color:#dbeafe}
        .demo td,.demo th{padding:8px 10px;font-size:12px;text-align:left}
        .demo table{width:100%;border-collapse:collapse}
        .demo tr{border-bottom:1px solid #e2e8f0}
        .demo th{background:#eff6ff;color:#1e40af;font-weight:600}
    </style>
</head>
<body>
    <div id="root">
        @yield('content')
    </div>

    @if (session('success'))
        <div class="toast" id="flash-toast">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="toast" style="background:var(--er);" id="flash-toast">{{ session('error') }}</div>
    @endif

    <script>
        const t = document.getElementById('flash-toast');
        if (t) {
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transition = 'opacity 0.4s';
                setTimeout(() => t.remove(), 400);
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>
</html>
