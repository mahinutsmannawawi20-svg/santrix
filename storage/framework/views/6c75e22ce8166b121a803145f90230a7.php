<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SANTRIX - Sistem Manajemen Pesantren Modern</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #ec4899;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --max-width: 1200px;
            --header-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-dark);
            background: var(--white);
            line-height: 1.6;
        }

        /* Container */
        .container {
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            height: var(--header-height);
            display: flex;
            align-items: center;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .logo span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-gray);
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        /* Sections */
        section {
            padding: 100px 0;
            scroll-margin-top: var(--header-height); /* For sticky header adjustment */
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-badge {
            background: #eef2ff;
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .section-desc {
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        /* Hero Section */
        #home {
            background: radial-gradient(circle at top right, #eef2ff 0%, transparent 40%),
                        radial-gradient(circle at bottom left, #fdf2f8 0%, transparent 40%);
            padding: 160px 0 100px;
            text-align: center;
            overflow: hidden;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #1e293b 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.25rem;
            color: var(--text-gray);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-image {
            margin-top: 60px;
            background: white;
            border-radius: 20px;
            padding: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }
        
        .hero-image img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            display: block;
        }

        /* Features Section */
        #features {
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
        }

        .feature-card {
            background: var(--bg-light);
            padding: 32px;
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .feature-card:hover {
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border-color: #e2e8f0;
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--primary);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .feature-text {
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        /* Pricing Section */
        #pricing {
            background: var(--bg-light);
            position: relative;
        }
        
        #pricing::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.05), transparent);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #e2e8f0;
            position: relative;
            transition: all 0.3s;
        }

        .pricing-card.featured {
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.1);
            transform: scale(1.05);
            z-index: 10;
        }
        
        .pricing-card.featured::before {
            content: 'Paling Populer';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 24px 0;
            color: var(--text-dark);
        }
        
        .price span {
            font-size: 1rem;
            color: var(--text-gray);
            font-weight: 500;
        }

        .price-features {
            list-style: none;
            margin-bottom: 32px;
        }

        .price-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        .price-features li i {
            color: #10b981;
            width: 18px;
            height: 18px;
        }

        .btn-block {
            display: flex;
            width: 100%;
            justify-content: center;
            background: #f1f5f9;
            color: var(--text-dark);
        }
        
        .pricing-card.featured .btn-block {
            background: var(--primary);
            color: white;
        }

        /* Contact Section */
        #contact {
            background: radial-gradient(circle at bottom center, #eef2ff 0%, white 50%);
            padding-bottom: 60px;
        }

        .contact-box {
            background: white;
            border-radius: 24px;
            padding: 48px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
            text-align: left;
        }
        
        .contact-item h4 {
            font-size: 1.1rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .contact-item p {
            color: var(--text-gray);
            margin-left: 28px;
        }

        /* Footer */
        .footer {
            background: #1e293b;
            color: white;
            padding: 60px 0 24px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        
        .footer-brand h3 {
            font-size: 1.5rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links ul li {
            margin-bottom: 12px;
        }
        
        .footer-links ul li a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-links ul li a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 24px;
            text-align: center;
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .nav-links {
                display: none; /* Hide for now, can perform mobile menu toggle later */
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="#" class="logo">
                <i data-feather="hexagon" style="width: 28px; height: 28px; color: var(--primary);"></i>
                SANTRIX
            </a>
            
            <ul class="nav-links">
                <li><a href="#home" class="nav-link active" data-target="home">Beranda</a></li>
                <li><a href="#features" class="nav-link" data-target="features">Fitur</a></li>
                <li><a href="#pricing" class="nav-link" data-target="pricing">Harga</a></li>
                <li><a href="#contact" class="nav-link" data-target="contact">Kontak</a></li>
            </ul>

            <div style="display: flex; gap: 16px;">
                <a href="<?php echo e(route('tenant.login')); ?>" style="color: var(--text-dark); text-decoration: none; font-weight: 500; padding: 10px;">Masuk</a>
                <a href="#pricing" class="btn-primary">Daftar Sekarang</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home">
        <div class="container">
            <div class="hero-content">
                <span class="section-badge">✨ Solusi Pesantren Digital v2.0</span>
                <h1 class="hero-title">Kelola Pesantren Jadi Lebih Modern & Mudah</h1>
                <p class="hero-desc">
                    Satu aplikasi untuk semua kebutuhan pesantren: Keuangan SPP, Akademik Santri, Perizinan, hingga Laporan Keuangan otomatis.
                </p>
                <div style="display: flex; gap: 16px; justify-content: center;">
                    <a href="#pricing" class="btn-primary" style="padding: 12px 32px; font-size: 1.1rem;">
                        Mulai Sekarang
                    </a>
                    <a href="#" style="padding: 12px 32px; background: white; border: 1px solid #e2e8f0; border-radius: 50px; text-decoration: none; color: var(--text-dark); font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                        <i data-feather="play-circle"></i> Lihat Demo
                    </a>
                </div>

                <div class="hero-image">
                    <!-- Placeholder Dashboard Image -->
                    <div style="aspect-ratio: 16/9; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.5rem; font-weight: 600;">
                        <img src="https://via.placeholder.com/1200x675/f1f5f9/94a3b8?text=Dashboard+SANTRIX+Preview" alt="Dashboard Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Fitur Unggulan</span>
                <h2 class="section-title">Semua yang Anda Butuhkan</h2>
                <p class="section-desc">Tidak perlu banyak aplikasi terpisah. Santrix mengintegrasikan semua aspek manajemen pesantren.</p>
            </div>

            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-feather="dollar-sign"></i>
                    </div>
                    <h3 class="feature-title">Keuangan & Billing</h3>
                    <p class="feature-text">
                        Kelola tagihan Syahriah/SPP otomatis. Kirim notifikasi tagihan ke WhatsApp wali santri hanya dengan satu klik.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-feather="book-open"></i>
                    </div>
                    <h3 class="feature-title">Akademik & Rapor</h3>
                    <p class="feature-text">
                        Input nilai, cetak rapor, dan kelola kenaikan kelas dengan mudah. Format rapor standar Depag/Diknas.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-feather="users"></i>
                    </div>
                    <h3 class="feature-title">Data Santri Terpusat</h3>
                    <p class="feature-text">
                        Database santri lengkap dengan riwayat kesehatan, perizinan, dan pelanggaran. Bisa diakses kapan saja.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-feather="message-circle"></i>
                    </div>
                    <h3 class="feature-title">WhatsApp Gateway</h3>
                    <p class="feature-text">
                        Kirim pengumuman, undangan, dan tagihan langsung ke nomor WhatsApp wali santri secara massal.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-feather="shield"></i>
                    </div>
                    <h3 class="feature-title">Aman & Cloud Based</h3>
                    <p class="feature-text">
                        Data tersimpan aman di cloud server dengan backup rutin. Tidak perlu takut laptop rusak atau data hilang.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-feather="smartphone"></i>
                    </div>
                    <h3 class="feature-title">Akses Wali Santri</h3>
                    <p class="feature-text">
                        Wali santri dapat memantau perkembangan anak dan riwayat pembayaran melalui portal khusus.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Biaya Langganan</span>
                <h2 class="section-title">Pilih Paket Sesuai Kebutuhan</h2>
                <p class="section-desc">Transparan, tanpa biaya tersembunyi. Upgrade kapan saja.</p>
            </div>

            <div class="pricing-grid">
                <!-- Basic -->
                <div class="pricing-card">
                    <h3 class="feature-title">Starter</h3>
                    <div class="price">Rp 199rb<span>/bulan</span></div>
                    <p style="color: var(--text-gray); margin-bottom: 24px;">Untuk pesantren rintisan < 100 santri.</p>
                    
                    <ul class="price-features">
                        <li><i data-feather="check-circle"></i> Data Santri (Max 100)</li>
                        <li><i data-feather="check-circle"></i> Keuangan Dasar</li>
                        <li><i data-feather="check-circle"></i> Akademik & Rapor</li>
                        <li><i data-feather="x-circle" style="color: #cbd5e1;"></i> WhatsApp Notif</li>
                    </ul>
                    
                    <a href="#" class="btn-primary btn-block">Pilih Paket</a>
                </div>

                <!-- Pro (Featured) -->
                <div class="pricing-card featured">
                    <h3 class="feature-title">Advance</h3>
                    <div class="price">Rp 499rb<span>/bulan</span></div>
                    <p style="color: var(--text-gray); margin-bottom: 24px;">Paling populer untuk pesantren berkembang.</p>
                    
                    <ul class="price-features">
                        <li><i data-feather="check-circle"></i> Data Santri (Unlimited)</li>
                        <li><i data-feather="check-circle"></i> Keuangan Lengkap</li>
                        <li><i data-feather="check-circle"></i> Akademik & Rapor</li>
                        <li><i data-feather="check-circle"></i> WhatsApp Billing & Notif</li>
                        <li><i data-feather="check-circle"></i> Prioritas Support</li>
                    </ul>
                    
                    <a href="#" class="btn-primary btn-block">Coba Gratis 14 Hari</a>
                </div>

                <!-- Enterprise -->
                <div class="pricing-card">
                    <h3 class="feature-title">Enterprise</h3>
                    <div class="price">Hubungi Kami</div>
                    <p style="color: var(--text-gray); margin-bottom: 24px;">Untuk yayasan besar dengan banyak cabang.</p>
                    
                    <ul class="price-features">
                        <li><i data-feather="check-circle"></i> Semua Fitur Advance</li>
                        <li><i data-feather="check-circle"></i> Custom Domain</li>
                        <li><i data-feather="check-circle"></i> Dedicated Server</li>
                        <li><i data-feather="check-circle"></i> White Label (Logo Sendiri)</li>
                    </ul>
                    
                    <a href="#contact" class="btn-primary btn-block" style="background: white; border: 1px solid var(--primary); color: var(--primary);">Konsultasi</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="contact-box">
                <span class="section-badge">Hubungi Kami</span>
                <h2 class="section-title">Butuh Bantuan atau Demo?</h2>
                <p class="section-desc">Tim kami siap membantu Anda memahami bagaimana Santrix dapat membantu pesantren Anda.</p>

                <div class="contact-grid">
                    <div class="contact-item">
                        <h4><i data-feather="message-circle" style="color: #25D366;"></i> WhatsApp Support</h4>
                        <p>+62 812-3456-7890 (Fast Response)</p>
                    </div>
                    <div class="contact-item">
                        <h4><i data-feather="mail" style="color: var(--primary);"></i> Email</h4>
                        <p>hello@santrix.id</p>
                    </div>
                    <div class="contact-item">
                        <h4><i data-feather="map-pin" style="color: #ef4444;"></i> Kantor</h4>
                        <p>Jl. Pesantren Modern No. 123, Jakarta Selatan</p>
                    </div>
                </div>

                <div style="margin-top: 40px;">
                    <a href="https://wa.me/" target="_blank" class="btn-primary" style="background: #25D366;">
                        Chat WhatsApp Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3>
                        <i data-feather="hexagon" style="width: 24px; height: 24px;"></i> SANTRIX
                    </h3>
                    <p style="color: #94a3b8; line-height: 1.6; max-width: 300px;">
                        Platform manajemen pesantren modern yang membantu digitalisasi administrasi pendidikan Islam di Indonesia.
                    </p>
                </div>
                
                <div class="footer-links">
                    <h4 style="margin-bottom: 20px; color: white;">Produk</h4>
                    <ul>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#pricing">Harga</a></li>
                        <li><a href="#">Patch Notes</a></li>
                        <li><a href="#">Roadmap</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4 style="margin-bottom: 20px; color: white;">Legal</h4>
                    <ul>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                &copy; <?php echo e(date('Y')); ?> SANTRIX. All rights reserved. Made with ❤️ for Pesantren Indonesia.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Use replace() immediately to render icons
        feather.replace();

        // Sticky Navbar Scrollspy logic
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            function onScroll() {
                let current = '';
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    // Offset for header height adjustment (100px buffer)
                    if (window.scrollY >= (sectionTop - 150)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.dataset.target === current) {
                        link.classList.add('active');
                    }
                });
                
                // If at top, make home active
                if (window.scrollY < 100) {
                    navLinks.forEach(l => l.classList.remove('active'));
                    document.querySelector('.nav-link[data-target="home"]').classList.add('active');
                }
            }

            window.addEventListener('scroll', onScroll);
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\v\.gemini\antigravity\scratch\santrix\resources\views/welcome.blade.php ENDPATH**/ ?>