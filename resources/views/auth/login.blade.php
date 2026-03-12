<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — NusaBiz</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #0891b2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 100%;
        }
        
        .logo-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .logo-img {
            max-width: 180px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #0891b2 100%);
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }
        
        .form-control {
            border-radius: 12px;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.4;
        }
        
        .animated-bg div {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(90deg); }
        }
        
        .animated-bg div:nth-child(1) { width: 400px; height: 400px; top: -10%; left: -5%; animation-delay: 0s; }
        .animated-bg div:nth-child(2) { width: 300px; height: 300px; bottom: -5%; right: -5%; animation-delay: 5s; }
    </style>
</head>
<body>
    <div class="animated-bg">
        <div></div>
        <div></div>
    </div>
    
    <div class="login-card">
        <div class="logo-container">
            <img src="{{ asset('img/logo-nusabiz.png') }}" alt="NusaBiz Logo" class="logo-img">
        </div>
        
        <h2 class="text-center fw-bold mb-2">NusaBiz Portal</h2>
        <p class="text-center text-secondary mb-4">Pilih peran & masukkan kredensial</p>
        
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="alert-circle" style="width: 20px;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <div class="d-flex gap-3 mb-4">
            <button type="button" onclick="setRole('admin')" id="btn-admin" class="flex-grow-1 btn btn-outline-primary py-3 fw-bold active" style="border-radius: 16px; transition: all 0.3s;">
                Admin
            </button>
            <button type="button" onclick="setRole('karyawan')" id="btn-karyawan" class="flex-grow-1 btn btn-outline-primary py-3 fw-bold" style="border-radius: 16px; transition: all 0.3s;">
                Karyawan
            </button>
        </div>
        
        <form method="POST" action="/login" id="loginForm">
            @csrf
            <input type="hidden" name="role_category" id="role_category" value="admin">
            
            <div class="mb-4">
                <label class="form-label fw-semibold text-gray-700" id="userLabel">Username / NIP Admin</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;">
                        <i data-lucide="user" style="width: 20px;"></i>
                    </span>
                    <input type="text" name="email" class="form-control" placeholder="Masukkan ID Anda" 
                           value="{{ old('email') }}" required autofocus 
                           style="border-radius: 0 12px 12px 0; border-left: none;">
                </div>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label fw-semibold text-gray-700">Kata Sandi</label>
                    <a href="#" class="small text-decoration-none">Lupa kata sandi?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0" style="border-radius: 12px 0 0 12px;">
                        <i data-lucide="lock" style="width: 20px;"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" 
                           required style="border-radius: 0 12px 12px 0; border-left: none;">
                </div>
            </div>
            
            <input type="hidden" name="device_uuid" id="device_uuid">
            
            <button type="submit" class="btn btn-primary w-100 mt-2 py-3 shadow-primary" style="border-radius: 16px;">
                Masuk Sistem
            </button>
        </form>
    </div>
    
    <style>
        .btn-outline-primary.active {
            background: linear-gradient(135deg, #4f46e5 0%, #0891b2 100%);
            color: white !important;
            border: none;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }
        .shadow-primary {
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);
        }
    </style>
    
    <script>
        lucide.createIcons();
        
        function setRole(role) {
            document.getElementById('role_category').value = role;
            document.getElementById('userLabel').innerText = role === 'admin' ? 'NIP / ID Khusus Bos & Pemilik Pusat' : 'NIP / ID Semua Karyawan';
            
            const btnAdmin = document.getElementById('btn-admin');
            const btnKaryawan = document.getElementById('btn-karyawan');
            
            if(role === 'admin') {
                btnAdmin.classList.add('active');
                btnKaryawan.classList.remove('active');
            } else {
                btnKaryawan.classList.add('active');
                btnAdmin.classList.remove('active');
            }
        }

        // Simple Device Fingerprint logic
        document.addEventListener('DOMContentLoaded', function() {
            const fingerprint = btoa([
                navigator.userAgent,
                navigator.language,
                screen.colorDepth,
                screen.width + 'x' + screen.height,
                new Date().getTimezoneOffset()
            ].join('|'));
            document.getElementById('device_uuid').value = fingerprint;
        });
    </script>
</body>
</html>
