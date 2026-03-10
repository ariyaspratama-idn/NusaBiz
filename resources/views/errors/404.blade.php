<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Outfit', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            max-width: 500px;
            padding: 40px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 800;
            background: linear-gradient(135deg, #4f46e5, #0891b2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0;
        }
        h1 { font-size: 24px; margin-top: 0; }
        p { color: #94a3b8; margin-bottom: 30px; }
        .btn {
            background: linear-gradient(135deg, #4f46e5, #0891b2);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s;
        }
        .btn:hover { transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <h1>Opps! Halaman Hilang</h1>
        <p>Halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan ke dimensi lain.</p>
        <a href="/" class="btn">
            <i data-lucide="arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
