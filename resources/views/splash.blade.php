<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpus Digital - Splash</title>
    <link rel="stylesheet" href="{{ asset('css/member-pages.css') }}">
    <style>
        :root{--wood:#8B5A2B;--cream:#FEF9F3;--text:#3B2A1A;--accent:#B63A2B}
        html,body{height:100%;margin:0;font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:linear-gradient(180deg,var(--cream) 0%, #fff 100%);color:var(--text)}
        .splash-wrap{height:100%;display:flex;align-items:center;justify-content:center;padding:24px}
        .card{width:100%;max-width:760px;background:linear-gradient(180deg,rgba(255,255,255,0.85),rgba(255,255,255,0.98));border-radius:16px;border:1px solid rgba(139,90,43,0.08);box-shadow:0 20px 50px rgba(139,90,43,0.06);display:flex;gap:20px;align-items:center;padding:28px}
        .logo-wrap{width:140px;height:140px;flex:0 0 140px;display:flex;align-items:center;justify-content:center}
        .logo-wrap img{max-width:100%;max-height:100%;border-radius:12px;object-fit:cover}
        .content{flex:1}
        .app-title{font-size:28px;font-weight:700;color:var(--wood)}
        .app-sub{margin-top:6px;color:rgba(59,42,26,0.85)}
        .features{margin-top:14px;color:rgba(59,42,26,0.9);font-size:14px}
        .actions{margin-top:18px;display:flex;gap:10px}
        .btn-primary{background:linear-gradient(135deg,var(--wood) 0%,var(--accent) 100%);color:#fff;padding:10px 16px;border-radius:10px;border:none;font-weight:600;cursor:pointer}
        .btn-ghost{background:transparent;border-radius:10px;padding:10px 14px;border:1px solid rgba(59,42,26,0.08);color:var(--text);cursor:pointer}
        .loading-dots{margin-left:8px;display:inline-block;vertical-align:middle}
        .dot{width:8px;height:8px;background:var(--wood);border-radius:50%;display:inline-block;margin-right:6px;opacity:.2;animation:blink 1s infinite}
        .dot:nth-child(2){animation-delay:.15s}
        .dot:nth-child(3){animation-delay:.3s}
        @keyframes blink{0%{opacity:.15;transform:translateY(0)}50%{opacity:1;transform:translateY(-6px)}100%{opacity:.15;transform:translateY(0)}}
        @media (max-width:640px){.card{flex-direction:column;align-items:center;text-align:center}.logo-wrap{width:120px;height:120px}}
    </style>
</head>
<body>
    <div class="splash-wrap">
        <div class="card">
            <div class="logo-wrap">
                <img src="{{ asset('logo_koshouko.jpeg') }}" alt="Logo">
            </div>
            <div class="content">
                <div class="app-title">Perpus Digital</div>
                <div class="app-sub">Platform perpustakaan digital — Temukan dan pinjam buku favoritmu</div>
                <div class="features">Akses koleksi, buat peminjaman, dan kelola profil dengan mudah.</div>
                <div class="actions">
                    <button class="btn-primary" onclick="goLogin()">Masuk sekarang <span class="loading-dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></button>
                    <button class="btn-ghost" onclick="goHome()">Jelajahi Publik</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goLogin(){ window.location.href = "{{ route('login') }}"; }
        function goHome(){ window.location.href = "{{ url('/books') }}"; }
        // Redirect to login after 2200ms
        setTimeout(goLogin, 2200);
    </script>
</body>
</html>
