<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SANTRIX - Kelola Pesantren Lebih Mudah</title>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        
        .register-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.05);
            max-width: 1000px;
            width: 100%;
            border: 1px solid #e2e8f0;
        }
        
        .register-form {
            padding: 48px;
        }
        
        .register-visual {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            padding: 48px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        .register-visual::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: #1e293b;
        }
        
        .form-input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            transition: all 0.2s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .input-group {
            display: flex;
            align-items: stretch;
        }
        
        .input-group .form-input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .input-addon {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-left: none;
            padding: 0 16px;
            display: flex;
            align-items: center;
            color: #64748b;
            font-size: 0.9rem;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn-submit:hover {
            background: #4338ca;
        }
        
        .logo {
            font-weight: 800;
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .error-msg {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        @media (max-width: 900px) {
            .register-container {
                grid-template-columns: 1fr;
            }
            .register-visual {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <!-- Form Section -->
        <div class="register-form">
            <a href="/" class="logo">
                <i data-feather="hexagon" style="color: #4f46e5;"></i> SANTRIX
            </a>
            
            <h2 style="font-size: 1.5rem; margin-bottom: 8px;">Mulai Kelola Pesantren Anda</h2>
            <p style="color: #64748b; margin-bottom: 32px; font-size: 0.95rem;">
                Daftar sekarang dan nikmati trial fitur lengkap gratis selama 14 hari.
            </p>

            @if(session('error'))
            <div style="background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 24px; font-size: 0.9rem;">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('register.tenant.store') }}" method="POST">
                @csrf
                
                <h4 style="margin-bottom: 16px; color: #94a3b8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Info Pesantren</h4>
                
                <div class="form-group">
                    <label class="form-label">Nama Pesantren</label>
                    <input type="text" name="nama_pesantren" class="form-input" placeholder="Contoh: Ponpes Al-Hidayah" value="{{ old('nama_pesantren') }}" required>
                    @error('nama_pesantren') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Website (Subdomain)</label>
                    <div class="input-group">
                        <input type="text" name="subdomain" class="form-input" placeholder="namapesantren" value="{{ old('subdomain') }}" required pattern="[a-z0-9-]+" title="Hanya huruf kecil, angka, dan dash (-)">
                        <span class="input-addon">.santrix.id</span>
                    </div>
                    <small style="color: #94a3b8; font-size: 0.8rem;">Gunakan huruf kecil tanpa spasi.</small>
                    @error('subdomain') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <h4 style="margin-bottom: 16px; margin-top: 24px; color: #94a3b8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Akun Pemilik</h4>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama Anda" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@pesantren.com" value="{{ old('email') }}" required>
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="phone" class="form-input" placeholder="081234..." value="{{ old('phone') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn-submit">Buat Akun Pesantren &rarr;</button>
                
                <p style="margin-top: 24px; text-align: center; font-size: 0.9rem; color: #64748b;">
                    Sudah punya akun? <a href="{{ route('login') }}" style="color: #4f46e5; font-weight: 600; text-decoration: none;">Masuk disini</a>
                </p>
            </form>
        </div>

        <!-- Visual Section -->
        <div class="register-visual">
            <div style="position: relative; z-index: 10;">
                <h3 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 16px;">Bergabung dengan 500+ Pesantren Modern</h3>
                <p style="opacity: 0.9; line-height: 1.6;">"Sejak pakai Santrix, laporan keuangan jadi rapi dan wali santri senang bisa cek nilai lewat HP."</p>
                <div style="margin-top: 16px; font-weight: 600;">— Ustadz Ahmad, Ponpes Nurul Iman</div>
            </div>
            
            <div style="position: relative; z-index: 10;">
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 24px; border-radius: 16px;">
                    <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                        <i data-feather="check-circle" style="color: #4ade80;"></i>
                        <span>Gratis Trial 14 Hari</span>
                    </div>
                    <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                        <i data-feather="check-circle" style="color: #4ade80;"></i>
                        <span>Tanpa Kartu Kredit</span>
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <i data-feather="check-circle" style="color: #4ade80;"></i>
                        <span>Support Prioritas via WA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
