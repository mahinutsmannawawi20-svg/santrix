<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Santrix - Solusi Pesantren Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">
    <!-- Navbar -->
    <nav class="bg-white border-b py-4">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <span class="text-2xl font-bold text-indigo-600">SANTRIX</span>
            <div class="hidden md:flex gap-6">
                <a href="#home" class="hover:text-indigo-600">Beranda</a>
                <a href="#features" class="hover:text-indigo-600">Fitur</a>
                <a href="#pricing" class="hover:text-indigo-600">Harga</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section id="home" class="py-20 text-center">
        <h1 class="text-5xl font-bold mb-6 text-slate-800">Sistem Manajemen <span class="text-indigo-600">Pesantren Modern</span></h1>
        <p class="text-xl text-slate-500 mb-8">Kelola SPP, Akademik, dan Keuangan dalam satu aplikasi.</p>
        <a href="{{ route('register.tenant') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700">Coba Gratis</a>
    </section>

    <!-- Pricing (Simplified Loop) -->
    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Paket Langganan</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($plans as $plan)
                <div class="border rounded-2xl p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-bold mb-2">{{ $plan['name'] }}</h3>
                    <div class="text-3xl font-bold text-indigo-600 mb-4">
                        @if(isset($plan['formatted_price']))
                            {{ $plan['formatted_price'] }}
                        @else
                            Rp {{ number_format($plan['price']/1000) }}rb
                        @endif
                    </div>
                    <p class="text-sm text-slate-500 mb-6">{{ $plan['description'] }}</p>
                    <a href="{{ route('register.tenant', ['package' => $plan['id']]) }}" class="block w-full py-3 bg-slate-100 text-center rounded-lg font-bold hover:bg-indigo-50 hover:text-indigo-600">Pilih Paket</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white py-8 text-center mt-20">
        <p>&copy; {{ date('Y') }} Santrix by Velora.</p>
    </footer>
</body>
</html>
