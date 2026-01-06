import { Head, Link } from '@inertiajs/react';

interface Stats {
    totalPesantren: number;
    totalSantri: number;
    totalUsers: number;
}

interface FeatureItem {
    name: string;
    included: boolean;
}

interface Package {
    id: number;
    name: string;
    price: number;
    duration_months: number;
    max_users: number;
    max_santri: number;
    features: FeatureItem[] | string;
}

interface WelcomeProps {
    stats: Stats;
    packages: Package[];
}

const features = [
    {
        icon: '👥',
        title: 'Sekretaris & TU',
        subtitle: 'Administrasi & Data Santri',
        tags: ['Data Santri', 'Asrama', 'Mutasi', 'Alumni'],
        color: 'violet',
        href: '/demo-start/sekretaris',
    },
    {
        icon: '💰',
        title: 'Bendahara',
        subtitle: 'Keuangan & Syahriah',
        tags: ['SPP', 'Pemasukan', 'Pengeluaran', 'Gaji'],
        color: 'emerald',
        href: '/demo-start/bendahara',
    },
    {
        icon: '📚',
        title: 'Pendidikan',
        subtitle: 'Akademik & Tahfidz',
        tags: ['Nilai', 'Absensi', 'Hafalan', 'Rapor'],
        color: 'sky',
        href: '/demo-start/pendidikan',
    },
    {
        icon: '🛡️',
        title: 'Admin & Yayasan',
        subtitle: 'Kontrol & Pengaturan',
        tags: ['User', 'Backup', 'Branding', 'WhatsApp'],
        color: 'slate',
        href: '/demo-start/admin',
    },
];

const colorClasses: Record<string, { bg: string; border: string; text: string; hover: string; tag: string; gradient: string }> = {
    violet: {
        bg: 'bg-gradient-to-br from-violet-50 via-white to-indigo-50',
        border: 'border-violet-100/50',
        text: 'text-violet-600',
        hover: 'hover:shadow-violet-100/50 group-hover:text-violet-700',
        tag: 'border-violet-100',
        gradient: 'from-violet-500 to-indigo-600',
    },
    emerald: {
        bg: 'bg-gradient-to-br from-emerald-50 via-white to-teal-50',
        border: 'border-emerald-100/50',
        text: 'text-emerald-600',
        hover: 'hover:shadow-emerald-100/50 group-hover:text-emerald-700',
        tag: 'border-emerald-100',
        gradient: 'from-emerald-500 to-teal-600',
    },
    sky: {
        bg: 'bg-gradient-to-br from-sky-50 via-white to-blue-50',
        border: 'border-sky-100/50',
        text: 'text-sky-600',
        hover: 'hover:shadow-sky-100/50 group-hover:text-sky-700',
        tag: 'border-sky-100',
        gradient: 'from-sky-500 to-blue-600',
    },
    slate: {
        bg: 'bg-gradient-to-br from-slate-50 via-white to-gray-50',
        border: 'border-slate-200/50',
        text: 'text-slate-600',
        hover: 'hover:shadow-slate-100/50 group-hover:text-slate-900',
        tag: 'border-slate-200',
        gradient: 'from-slate-600 to-slate-800',
    },
};

export default function Welcome({ stats, packages }: WelcomeProps) {
    // Default values if stats is undefined
    const safeStats = {
        totalPesantren: stats?.totalPesantren ?? 0,
        totalSantri: stats?.totalSantri ?? 0,
        totalUsers: stats?.totalUsers ?? 0,
    };

    const formatNumber = (num: number) => {
        return new Intl.NumberFormat('id-ID').format(num);
    };

    const formatPrice = (price: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(price);
    };

    // Helper to safely get features array (handles objects with name property or strings)
    const getFeatures = (features: unknown): string[] => {
        if (!features) return [];
        if (typeof features === 'string') {
            try {
                const parsed = JSON.parse(features);
                if (Array.isArray(parsed)) {
                    return parsed.map((f: unknown) =>
                        typeof f === 'object' && f !== null && 'name' in f
                            ? String((f as { name: unknown }).name)
                            : String(f)
                    );
                }
                return [];
            } catch {
                return [];
            }
        }
        if (Array.isArray(features)) {
            return features.map((f: unknown) =>
                typeof f === 'object' && f !== null && 'name' in f
                    ? String((f as { name: unknown }).name)
                    : String(f)
            );
        }
        return [];
    };

    return (
        <>
            <Head title="Sistem Manajemen Pesantren Modern" />

            {/* Navbar */}
            <nav className="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center">
                                <span className="text-white font-bold text-lg">S</span>
                            </div>
                            <span className="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                                Santrix
                            </span>
                        </div>
                        <div className="hidden md:flex items-center gap-8">
                            <a href="#features" className="text-slate-600 hover:text-slate-900 font-medium transition-colors">Fitur</a>
                            <a href="#pricing" className="text-slate-600 hover:text-slate-900 font-medium transition-colors">Harga</a>
                            <a href="#contact" className="text-slate-600 hover:text-slate-900 font-medium transition-colors">Kontak</a>
                        </div>
                        <div className="flex items-center gap-3">
                            <Link
                                href="/login"
                                className="px-4 py-2 text-slate-600 hover:text-slate-900 font-medium transition-colors"
                            >
                                Masuk
                            </Link>
                            <Link
                                href="/register-pesantren"
                                className="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-indigo-200 transition-all"
                            >
                                Daftar Gratis
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <section className="relative min-h-screen flex items-center pt-16 overflow-hidden">
                {/* Background Decorations */}
                <div className="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-indigo-50"></div>
                <div className="absolute top-20 left-10 w-72 h-72 bg-violet-200 rounded-full blur-3xl opacity-30"></div>
                <div className="absolute bottom-20 right-10 w-96 h-96 bg-indigo-200 rounded-full blur-3xl opacity-30"></div>

                {/* Islamic Pattern Overlay */}
                <div className="absolute inset-0 opacity-[0.02]" style={{
                    backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%236366f1' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`
                }}></div>

                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                    <div className="text-center max-w-4xl mx-auto">
                        {/* Badge */}
                        <div className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-full text-indigo-700 font-medium text-sm mb-8">
                            <span className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            Digunakan oleh {formatNumber(safeStats.totalPesantren)}+ Pesantren
                        </div>

                        {/* Headline */}
                        <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                            Kelola Pesantren dengan
                            <span className="block bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent">
                                Lebih Mudah & Modern
                            </span>
                        </h1>

                        <p className="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto mb-10">
                            Sistem manajemen pesantren terlengkap. Dari administrasi santri, keuangan, pendidikan,
                            hingga laporan - semua dalam satu platform.
                        </p>

                        {/* CTA Buttons */}
                        <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                            <Link
                                href="/register-pesantren"
                                className="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-indigo-200/50 transition-all transform hover:-translate-y-1"
                            >
                                Mulai Gratis Sekarang
                            </Link>
                            <Link
                                href="/demo-start/sekretaris"
                                className="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 font-bold rounded-2xl border-2 border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all"
                            >
                                Coba Demo →
                            </Link>
                        </div>

                        {/* Stats */}
                        <div className="grid grid-cols-3 gap-8 max-w-lg mx-auto">
                            <div className="text-center">
                                <div className="text-3xl sm:text-4xl font-extrabold text-indigo-600">{formatNumber(safeStats.totalPesantren)}+</div>
                                <div className="text-sm text-slate-500 font-medium">Pesantren</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl sm:text-4xl font-extrabold text-violet-600">{formatNumber(safeStats.totalSantri)}+</div>
                                <div className="text-sm text-slate-500 font-medium">Santri</div>
                            </div>
                            <div className="text-center">
                                <div className="text-3xl sm:text-4xl font-extrabold text-purple-600">{formatNumber(safeStats.totalUsers)}+</div>
                                <div className="text-sm text-slate-500 font-medium">Pengguna</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Dashboard Preview Section */}
            <section className="py-16 bg-gradient-to-b from-white to-slate-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-12">
                        <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4">
                            Tampilan Dashboard Modern
                        </h2>
                        <p className="text-slate-600">
                            Interface yang bersih dan mudah digunakan untuk semua level pengguna
                        </p>
                    </div>

                    {/* Dashboard Mockup */}
                    <div className="relative max-w-5xl mx-auto">
                        <div className="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl p-1 shadow-2xl shadow-indigo-200">
                            <div className="bg-slate-900 rounded-xl overflow-hidden">
                                {/* Browser Top Bar */}
                                <div className="bg-slate-800 px-4 py-3 flex items-center gap-2">
                                    <div className="flex gap-1.5">
                                        <div className="w-3 h-3 rounded-full bg-red-500"></div>
                                        <div className="w-3 h-3 rounded-full bg-yellow-500"></div>
                                        <div className="w-3 h-3 rounded-full bg-green-500"></div>
                                    </div>
                                    <div className="flex-1 bg-slate-700 rounded-lg px-4 py-1.5 text-slate-400 text-sm text-center">
                                        santrix.my.id/dashboard
                                    </div>
                                </div>
                                {/* Dashboard Content Preview */}
                                <div className="bg-slate-100 p-6">
                                    <div className="grid grid-cols-4 gap-4 mb-4">
                                        <div className="bg-white rounded-lg p-4 shadow-sm">
                                            <div className="text-2xl font-bold text-indigo-600">245</div>
                                            <div className="text-xs text-slate-500">Total Santri</div>
                                        </div>
                                        <div className="bg-white rounded-lg p-4 shadow-sm">
                                            <div className="text-2xl font-bold text-emerald-600">12.5jt</div>
                                            <div className="text-xs text-slate-500">Pemasukan</div>
                                        </div>
                                        <div className="bg-white rounded-lg p-4 shadow-sm">
                                            <div className="text-2xl font-bold text-violet-600">98%</div>
                                            <div className="text-xs text-slate-500">Kehadiran</div>
                                        </div>
                                        <div className="bg-white rounded-lg p-4 shadow-sm">
                                            <div className="text-2xl font-bold text-orange-600">15</div>
                                            <div className="text-xs text-slate-500">Kelas Aktif</div>
                                        </div>
                                    </div>
                                    <div className="grid grid-cols-3 gap-4">
                                        <div className="col-span-2 bg-white rounded-lg p-4 shadow-sm h-32"></div>
                                        <div className="bg-white rounded-lg p-4 shadow-sm h-32"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Decorative Elements */}
                        <div className="absolute -bottom-4 -right-4 w-24 h-24 bg-indigo-100 rounded-2xl -z-10"></div>
                        <div className="absolute -top-4 -left-4 w-16 h-16 bg-violet-100 rounded-xl -z-10"></div>
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section id="features" className="py-24 bg-white relative overflow-hidden">
                <div className="absolute top-0 left-0 w-96 h-96 bg-indigo-50 rounded-full blur-3xl opacity-50 -translate-x-1/2 -translate-y-1/2"></div>
                <div className="absolute bottom-0 right-0 w-96 h-96 bg-violet-50 rounded-full blur-3xl opacity-50 translate-x-1/2 translate-y-1/2"></div>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div className="text-center max-w-3xl mx-auto mb-16">
                        <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                            Dashboard Profesional
                        </h2>
                        <p className="text-lg text-slate-600">
                            Akses terpisah untuk setiap divisi. Lebih fokus, lebih aman.
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 gap-8">
                        {features.map((feature) => {
                            const colors = colorClasses[feature.color];
                            return (
                                <a
                                    key={feature.title}
                                    href={feature.href}
                                    className="group block"
                                >
                                    <div className={`relative ${colors.bg} rounded-3xl border ${colors.border} shadow-sm hover:shadow-xl ${colors.hover} transition-all duration-500 overflow-hidden p-8`}>
                                        <div className="flex items-start gap-5">
                                            <div className={`w-16 h-16 rounded-2xl bg-gradient-to-br ${colors.gradient} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                                                <span className="text-3xl">{feature.icon}</span>
                                            </div>
                                            <div className="flex-1">
                                                <h3 className={`text-xl font-bold text-slate-800 mb-1 ${colors.hover} transition-colors`}>
                                                    {feature.title}
                                                </h3>
                                                <p className={`${colors.text}/80 text-sm font-medium mb-4`}>
                                                    {feature.subtitle}
                                                </p>
                                                <div className="flex flex-wrap gap-2">
                                                    {feature.tags.map((tag) => (
                                                        <span
                                                            key={tag}
                                                            className={`px-3 py-1 bg-white/80 text-xs font-medium text-slate-600 rounded-full border ${colors.tag}`}
                                                        >
                                                            {tag}
                                                        </span>
                                                    ))}
                                                </div>
                                            </div>
                                        </div>

                                        <div className={`mt-6 pt-5 border-t ${colors.border} flex items-center justify-between`}>
                                            <span className="text-sm text-slate-500">Klik untuk mencoba demo</span>
                                            <div className={`flex items-center gap-2 ${colors.text} font-semibold group-hover:translate-x-1 transition-transform`}>
                                                Masuk <span className="text-lg">→</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            );
                        })}
                    </div>
                </div>
            </section>

            {/* Pricing Section */}
            <section id="pricing" className="py-24 bg-gradient-to-br from-slate-50 to-indigo-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-16">
                        <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                            Pilih Paket Terbaik
                        </h2>
                        <p className="text-lg text-slate-600">
                            Harga terjangkau untuk semua ukuran pesantren
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                        {packages.map((pkg, index) => (
                            <div
                                key={pkg.id}
                                className={`relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all p-8 ${index === 1 ? 'ring-2 ring-indigo-600 scale-105' : ''
                                    }`}
                            >
                                {index === 1 && (
                                    <div className="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-full">
                                        Populer
                                    </div>
                                )}
                                <div className="text-center mb-6">
                                    <h3 className="text-xl font-bold text-slate-900 mb-2">{pkg.name}</h3>
                                    <div className="text-3xl font-extrabold text-indigo-600">
                                        {formatPrice(pkg.price)}
                                    </div>
                                    <div className="text-sm text-slate-500">/ {pkg.duration_months} bulan</div>
                                </div>
                                <ul className="space-y-3 mb-8">
                                    <li className="flex items-center gap-2 text-slate-600">
                                        <span className="text-green-500">✓</span>
                                        Maks {pkg.max_santri} Santri
                                    </li>
                                    <li className="flex items-center gap-2 text-slate-600">
                                        <span className="text-green-500">✓</span>
                                        Maks {pkg.max_users} User
                                    </li>
                                    {getFeatures(pkg.features).slice(0, 3).map((feature) => (
                                        <li key={feature} className="flex items-center gap-2 text-slate-600">
                                            <span className="text-green-500">✓</span>
                                            {feature}
                                        </li>
                                    ))}
                                </ul>
                                <Link
                                    href="/register-pesantren"
                                    className={`block w-full py-3 text-center font-bold rounded-xl transition-all ${index === 1
                                        ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white hover:shadow-lg'
                                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                        }`}
                                >
                                    Pilih Paket
                                </Link>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Testimonials Section */}
            <section className="py-24 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center max-w-3xl mx-auto mb-16">
                        <h2 className="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                            Dipercaya Pesantren Seluruh Indonesia
                        </h2>
                        <p className="text-lg text-slate-600">
                            Testimoni dari pengguna Santrix
                        </p>
                    </div>

                    <div className="grid md:grid-cols-3 gap-8">
                        {[
                            {
                                name: 'Ust. Ahmad Fauzi',
                                role: 'Mudir PP. Riyadlul Huda',
                                text: 'Santrix sangat membantu kami mengelola data santri dan keuangan pesantren. Semuanya jadi lebih rapi dan terorganisir.',
                            },
                            {
                                name: 'Ust. Muhammad Rizki',
                                role: 'Kepala TU PP. Darul Ulum',
                                text: 'Fitur laporan otomatis sangat menghemat waktu kami. Sekarang tidak perlu lagi input manual ke Excel.',
                            },
                            {
                                name: 'Ustadzah Siti Aminah',
                                role: 'Bendahara PP. Nurul Hikmah',
                                text: 'Tracking pembayaran SPP jadi mudah. Wali santri juga bisa cek tagihan langsung via WhatsApp.',
                            },
                        ].map((testimonial, index) => (
                            <div
                                key={index}
                                className="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div className="flex items-center gap-1 mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <span key={i} className="text-yellow-400">★</span>
                                    ))}
                                </div>
                                <p className="text-slate-600 mb-6 italic">
                                    "{testimonial.text}"
                                </p>
                                <div className="flex items-center gap-4">
                                    <div className="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {testimonial.name.charAt(0)}
                                    </div>
                                    <div>
                                        <div className="font-bold text-slate-900">{testimonial.name}</div>
                                        <div className="text-sm text-slate-500">{testimonial.role}</div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer id="contact" className="bg-slate-900 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-4 gap-12">
                        <div className="col-span-2">
                            <div className="flex items-center gap-3 mb-4">
                                <div className="w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center">
                                    <span className="text-white font-bold text-lg">S</span>
                                </div>
                                <span className="text-xl font-bold">Santrix</span>
                            </div>
                            <p className="text-slate-400 max-w-md">
                                Sistem manajemen pesantren modern yang membantu ribuan pesantren di Indonesia
                                mengelola administrasi dengan lebih efisien.
                            </p>
                        </div>
                        <div>
                            <h4 className="font-bold mb-4">Produk</h4>
                            <ul className="space-y-2 text-slate-400">
                                <li><a href="#features" className="hover:text-white transition-colors">Fitur</a></li>
                                <li><a href="#pricing" className="hover:text-white transition-colors">Harga</a></li>
                                <li><a href="/demo-start" className="hover:text-white transition-colors">Demo</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold mb-4">Kontak</h4>
                            <ul className="space-y-2 text-slate-400">
                                <li>support@santrix.my.id</li>
                                <li>+62 812-3456-7890</li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-slate-800 mt-12 pt-8 text-center text-slate-500">
                        <p>© 2025 Santrix. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </>
    );
}
