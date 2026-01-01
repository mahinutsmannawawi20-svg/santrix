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
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-50 antialiased">

    <div class="min-h-screen py-6 sm:py-12 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header with Back Button -->
            <div class="flex items-center justify-between mb-8 sm:mb-10">
                <a href="/" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl hover:bg-white/80 transition-all duration-200">
                    <i data-feather="arrow-left" class="w-5 h-5 text-slate-600"></i>
                    <span class="hidden sm:inline text-sm font-semibold text-slate-700">Kembali</span>
                </a>
                
                <div class="flex items-center gap-2.5">
                    <img src="{{ asset('images/default-logo.png') }}" alt="SANTRIX" class="h-8 sm:h-10 w-auto">
                    <span class="text-xl sm:text-2xl font-bold text-slate-900">SANTRIX</span>
                </div>
                
                <div class="w-20 sm:w-28"></div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-500/10 overflow-hidden border border-slate-100">
                
                <!-- Hero Section -->
                <div class="bg-gradient-to-br from-indigo-600 via-indigo-600 to-violet-600 px-6 sm:px-12 py-10 sm:py-14 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full -mr-36 -mt-36 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-56 h-56 bg-white/10 rounded-full -ml-28 -mb-28 blur-3xl"></div>
                    
                    <div class="relative z-10 max-w-2xl">
                        <div class="inline-flex items-center gap-2.5 bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full mb-5">
                            <i data-feather="package" class="w-4 h-4"></i>
                            <span class="text-sm font-bold">{{ $selectedPlan['name'] }} - {{ $selectedPlan['duration_months'] }} Bulan</span>
                        </div>
                        
                        <h1 class="text-3xl sm:text-4xl font-extrabold mb-4">Buat Akun Pesantren</h1>
                        
                        @php
                            $trialDays = ($selectedPlan['duration_months'] == 3) ? 2 : 4;
                        @endphp
                        
                        <p class="text-base sm:text-lg text-indigo-50 mb-8 leading-relaxed">
                            Dapatkan akses <strong class="text-white">trial {{ $trialDays }} hari gratis</strong> untuk mencoba semua fitur
                        </p>
                        
                        <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                            <div class="flex items-center gap-2 text-sm bg-white/10 backdrop-blur-sm px-4 py-2.5 rounded-lg">
                                <i data-feather="check-circle" class="w-4 h-4 text-emerald-300"></i>
                                <span>Tanpa Kartu Kredit</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm bg-white/10 backdrop-blur-sm px-4 py-2.5 rounded-lg">
                                <i data-feather="zap" class="w-4 h-4 text-amber-300"></i>
                                <span>Setup Instant</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm bg-white/10 backdrop-blur-sm px-4 py-2.5 rounded-lg">
                                <i data-feather="shield" class="w-4 h-4 text-blue-300"></i>
                                <span>Data Aman</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="px-6 sm:px-12 py-10 sm:py-14">
                    
                    @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-5 py-4 rounded-xl mb-8 flex items-start gap-3">
                        <i data-feather="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm leading-relaxed">{{ session('error') }}</div>
                    </div>
                    @endif

                    <form action="{{ route('register.tenant.store') }}" method="POST" class="space-y-12">
                        @csrf
                        <input type="hidden" name="package" value="{{ $package }}">
                        
                        <!-- Pesantren Info -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 pb-4 border-b-2 border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i data-feather="home" class="w-6 h-6 text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">Data Pesantren</h3>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Informasi dasar pesantren Anda</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        Nama Pesantren <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_pesantren" value="{{ old('nama_pesantren') }}" 
                                        class="w-full px-4 py-3.5 text-sm sm:text-base border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all hover:border-slate-400 bg-white" 
                                        placeholder="Contoh: Pondok Pesantren Al-Hidayah" required>
                                    @error('nama_pesantren') <div class="text-red-600 text-xs mt-2 flex items-center gap-1.5"><i data-feather="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</div> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        Subdomain (Alamat Website) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-stretch">
                                        <input type="text" name="subdomain" value="{{ old('subdomain') }}" 
                                            class="flex-1 px-4 py-3.5 text-sm sm:text-base border-2 border-r-0 border-slate-300 rounded-l-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-slate-400 bg-white" 
                                            placeholder="namapesantren" pattern="[a-z0-9-]+" required>
                                        <span class="px-4 py-3.5 bg-slate-200 border-2 border-l-0 border-slate-300 rounded-r-xl text-slate-700 text-sm sm:text-base font-semibold flex items-center">.santrix.my.id</span>
                                    </div>
                                    <small class="text-xs text-slate-500 mt-2.5 flex items-center gap-1.5">
                                        <i data-feather="info" class="w-3.5 h-3.5"></i>
                                        Hanya huruf kecil, angka, dan tanda hubung (-)
                                    </small>
                                    @error('subdomain') <div class="text-red-600 text-xs mt-2 flex items-center gap-1.5"><i data-feather="alert-circle" class="w-3.5 h-3.5"></i>{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Owner Info -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 pb-4 border-b-2 border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i data-feather="user" class="w-6 h-6 text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">Akun Pemilik</h3>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Informasi login Anda sebagai owner</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" 
                                        class="w-full px-4 py-3.5 text-sm sm:text-base border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-slate-400 bg-white" 
                                        placeholder="Nama Anda" required>
                                    @error('name') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" 
                                        class="w-full px-4 py-3.5 text-sm sm:text-base border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-slate-400 bg-white" 
                                        placeholder="email@pesantren.com" required>
                                    @error('email') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        No. WhatsApp <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" 
                                        class="w-full px-4 py-3.5 text-sm sm:text-base border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-slate-400 bg-white" 
                                        placeholder="081234567890" required>
                                    @error('phone') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password" 
                                        class="w-full px-4 py-3.5 text-sm sm:text-base border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-slate-400 bg-white" 
                                        placeholder="Minimal 8 karakter" required>
                                    @error('password') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" 
                                        class="w-full px-4 py-3.5 text-sm sm:text-base border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-slate-400 bg-white" 
                                        placeholder="Ulangi password" required>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Details (Conditional for Advance) -->
                        @if(str_starts_with($package, 'advance'))
                        <div class="space-y-6">
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 sm:p-8 border-2 border-amber-200">
                                <div class="flex items-start gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0">
                                        <i data-feather="credit-card" class="w-6 h-6 text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg sm:text-xl font-bold text-amber-900 mb-1">Rekening Pencairan Dana</h3>
                                        <p class="text-xs sm:text-sm text-amber-700 leading-relaxed">Khusus paket Advance - diperlukan untuk pencairan saldo dari pembayaran santri</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                            Nama Bank <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                            class="w-full px-4 py-3.5 text-sm border-2 border-amber-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                            placeholder="BCA, BRI, Mandiri" required>
                                        @error('bank_name') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                            No. Rekening <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" 
                                            class="w-full px-4 py-3.5 text-sm border-2 border-amber-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                            placeholder="1234567890" required>
                                        @error('bank_account_number') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-800 mb-2.5">
                                            Atas Nama <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" 
                                            class="w-full px-4 py-3.5 text-sm border-2 border-amber-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                            placeholder="Nama Pemilik" required>
                                        @error('bank_account_name') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit" class="group w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-5 sm:py-6 rounded-2xl transition-all duration-300 shadow-xl shadow-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-600/40 flex items-center justify-center gap-3 transform hover:-translate-y-0.5">
                                <i data-feather="rocket" class="w-5 h-5 sm:w-6 sm:h-6 group-hover:rotate-12 transition-transform"></i>
                                <span class="text-base sm:text-lg">Buat Akun & Mulai Trial {{ $trialDays }} Hari</span>
                            </button>

                            <p class="text-center text-sm text-slate-600 mt-8">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 hover:underline inline-flex items-center gap-1">
                                    Masuk disini
                                    <i data-feather="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-10 text-sm text-slate-500">
                <p>Dengan mendaftar, Anda menyetujui <a href="#" class="text-indigo-600 hover:underline font-medium">Syarat & Ketentuan</a> kami</p>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
