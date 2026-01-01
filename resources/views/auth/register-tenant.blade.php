<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SANTRIX - {{ $selectedPlan['name'] ?? 'Paket' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-2 text-xl font-bold text-slate-900 mb-4">
                    <img src="{{ asset('images/default-logo.png') }}" alt="SANTRIX" class="h-8 w-auto">
                    SANTRIX
                </a>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Buat Akun Pesantren Anda</h1>
                <p class="text-slate-600">Nikmati trial <strong>7 hari gratis</strong> untuk paket {{ $selectedPlan['name'] }}</p>
            </div>

            <!-- Package Indicator -->
            <div class="bg-white rounded-2xl p-6 shadow-lg mb-6 border-2 border-indigo-200">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-1">Paket Dipilih</div>
                        <div class="text-2xl font-bold text-slate-900">{{ $selectedPlan['name'] }}</div>
                        <div class="text-slate-600 mt-1">{{ $selectedPlan['formatted_price'] ?? 'Rp ' . number_format($selectedPlan['price']) }} <span class="text-sm">/ {{ $selectedPlan['period'] }}</span></div>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-4">
                        <i data-feather="package" class="text-indigo-600 w-8 h-8"></i>
                    </div>
                </div>
            </div>

            @if(session('error'))
            <div class="bg-red-50 border-2 border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                {{ session('error') }}
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('register.tenant.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
                @csrf
                <input type="hidden" name="package" value="{{ $package }}">
                
                <!-- Pesantren Info -->
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i data-feather="home" class="w-5 h-5 text-indigo-600"></i>
                    Data Pesantren
                </h3>
                
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pesantren *</label>
                        <input type="text" name="nama_pesantren" value="{{ old('nama_pesantren') }}" 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                            placeholder="Contoh: Ponpes Al-Hidayah" required>
                        @error('nama_pesantren') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Subdomain (URL) *</label>
                        <div class="flex">
                            <input type="text" name="subdomain" value="{{ old('subdomain') }}" 
                                class="flex-1 px-4 py-2.5 border border-slate-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="namapesantren" pattern="[a-z0-9-]+" required>
                            <span class="px-4 py-2.5 bg-slate-100 border border-l-0 border-slate-300 rounded-r-lg text-slate-600">.santrix.my.id</span>
                        </div>
                        <small class="text-slate-500 text-xs">Huruf kecil, angka, dan dash (-) saja</small>
                        @error('subdomain') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Owner Info -->
                <h3 class="text-lg font-bold text-slate-900 mb-4 mt-8 flex items-center gap-2">
                    <i data-feather="user" class="w-5 h-5 text-indigo-600"></i>
                    Akun Pemilik
                </h3>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="Nama Anda" required>
                        @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="email@pesantren.com" required>
                        @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">No. WhatsApp *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="081234567890" required>
                        @error('phone') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password *</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="Minimal 8 karakter" required>
                        @error('password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                            required>
                    </div>
                </div>

                <!-- Bank Details (Conditional for Advance) -->
                @if($package === 'advance')
                <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-bold text-amber-900 mb-2 flex items-center gap-2">
                        <i data-feather="credit-card" class="w-5 h-5"></i>
                        Rekening Pencairan Dana (Advance Only)
                    </h3>
                    <p class="text-amber-700 text-sm mb-4">Diperlukan untuk pengajuan penarikan saldo dari pembayaran santri.</p>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Bank *</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                                placeholder="BCA, BRI, Mandiri, dll" required>
                            @error('bank_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">No. Rekening *</label>
                            <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" 
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                                placeholder="1234567890" required>
                            @error('bank_account_number') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Atas Nama *</label>
                            <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" 
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                                placeholder="Nama Pemilik Rekening" required>
                            @error('bank_account_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <i data-feather="check-circle" class="w-5 h-5"></i>
                    Buat Akun & Mulai Trial 7 Hari
                </button>

                <p class="text-center text-sm text-slate-600 mt-6">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Masuk disini</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
