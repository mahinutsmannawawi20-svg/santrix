import { Head, Link } from '@inertiajs/react';
import { motion, useScroll, useTransform, AnimatePresence } from 'framer-motion';
import { useRef, useState, useEffect } from 'react';

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

// Smooth easing
const smoothEase = [0.25, 0.1, 0.25, 1];

// Animation variants
const fadeInUp = {
    hidden: { opacity: 0, y: 40 },
    visible: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.7, ease: smoothEase }
    }
};

const fadeIn = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: { duration: 0.6, ease: smoothEase }
    }
};

const staggerContainer = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: { staggerChildren: 0.12 }
    }
};

const scaleIn = {
    hidden: { opacity: 0, scale: 0.9 },
    visible: {
        opacity: 1,
        scale: 1,
        transition: { duration: 0.5, ease: smoothEase }
    }
};

// SVG Icons
const Icons = {
    menu: (
        <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    ),
    close: (
        <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
        </svg>
    ),
    users: (
        <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
    ),
    money: (
        <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    ),
    book: (
        <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
    ),
    shield: (
        <svg className="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
    ),
    check: (
        <svg className="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
        </svg>
    ),
    arrow: (
        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
    ),
    star: (
        <svg className="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
    ),
    mosque: (
        <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 3c-1.5 2-2.5 3.5-2.5 5.5a2.5 2.5 0 005 0C14.5 6.5 13.5 5 12 3zM6 21V11c0-1 .5-2.5 2-3.5M18 21V11c0-1-.5-2.5-2-3.5M4 21V14c0-.5.5-1 1-1.5M20 21V14c0-.5-.5-1-1-1.5M3 21h18" />
        </svg>
    ),
};

const dashboardFeatures = [
    {
        icon: Icons.users,
        title: 'Sekretaris & TU',
        subtitle: 'Administrasi & Data Santri',
        description: 'Kelola data lengkap santri, asrama, mutasi, perizinan, dan alumni dalam satu sistem terintegrasi.',
        tags: ['Data Santri', 'Asrama', 'Mutasi', 'Perizinan'],
        color: 'bg-gradient-to-br from-indigo-500 to-blue-600',
        lightBg: 'bg-indigo-50',
        textColor: 'text-indigo-600',
        href: '/demo-start/sekretaris',
    },
    {
        icon: Icons.money,
        title: 'Bendahara',
        subtitle: 'Keuangan & Syahriah',
        description: 'Tracking pembayaran SPP real-time, laporan keuangan otomatis, dan integrasi notifikasi WhatsApp.',
        tags: ['SPP', 'Pemasukan', 'Pengeluaran', 'Gaji'],
        color: 'bg-gradient-to-br from-emerald-500 to-teal-600',
        lightBg: 'bg-emerald-50',
        textColor: 'text-emerald-600',
        href: '/demo-start/bendahara',
    },
    {
        icon: Icons.book,
        title: 'Pendidikan',
        subtitle: 'Akademik & Tahfidz',
        description: 'Sistem akademik lengkap dari input nilai, absensi, hafalan Al-Quran, hingga cetak rapor otomatis.',
        tags: ['Nilai', 'Absensi', 'Hafalan', 'Rapor'],
        color: 'bg-gradient-to-br from-sky-500 to-cyan-600',
        lightBg: 'bg-sky-50',
        textColor: 'text-sky-600',
        href: '/demo-start/pendidikan',
    },
    {
        icon: Icons.shield,
        title: 'Admin & Yayasan',
        subtitle: 'Kontrol & Pengaturan',
        description: 'Kelola pengguna, backup data, kustomisasi branding, dan integrasi WhatsApp Business API.',
        tags: ['User', 'Backup', 'Branding', 'Laporan'],
        color: 'bg-gradient-to-br from-slate-600 to-slate-800',
        lightBg: 'bg-slate-100',
        textColor: 'text-slate-700',
        href: '/demo-start/admin',
    },
];

const testimonials = [
    {
        name: 'Ust. Ahmad Fauzi',
        role: 'Mudir PP. Riyadlul Huda',
        text: 'Santrix sangat membantu kami mengelola data santri dan keuangan pesantren. Semuanya jadi lebih rapi dan terorganisir.',
        avatar: 'AF',
    },
    {
        name: 'Ust. Muhammad Rizki',
        role: 'Kepala TU PP. Darul Ulum',
        text: 'Fitur laporan otomatis sangat menghemat waktu kami. Tidak perlu lagi input manual ke Excel setiap bulan.',
        avatar: 'MR',
    },
    {
        name: 'Ustadzah Siti Aminah',
        role: 'Bendahara PP. Nurul Hikmah',
        text: 'Tracking pembayaran SPP jadi mudah. Wali santri bisa cek tagihan langsung via WhatsApp.',
        avatar: 'SA',
    },
];

export default function Welcome({ stats, packages }: WelcomeProps) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const heroRef = useRef<HTMLDivElement>(null);

    const { scrollYProgress } = useScroll({
        target: heroRef,
        offset: ['start start', 'end start']
    });

    const heroOpacity = useTransform(scrollYProgress, [0, 0.5], [1, 0]);
    const heroScale = useTransform(scrollYProgress, [0, 0.5], [1, 0.95]);

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 50);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const safeStats = {
        totalPesantren: stats?.totalPesantren ?? 0,
        totalSantri: stats?.totalSantri ?? 0,
        totalUsers: stats?.totalUsers ?? 0,
    };

    const formatNumber = (num: number) => new Intl.NumberFormat('id-ID').format(num);

    const formatPrice = (price: number) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);

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

    const scrollToSection = (id: string) => {
        setMobileMenuOpen(false);
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    };

    return (
        <>
            <Head title="Sistem Manajemen Pesantren Modern" />

            {/* Google Fonts */}
            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
                
                * {
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                }
                
                html {
                    scroll-behavior: smooth;
                }
                
                .gradient-text {
                    background: linear-gradient(135deg, #059669 0%, #0891b2 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }
            `}</style>

            {/* Navbar */}
            <motion.nav
                className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${scrolled ? 'bg-white/95 backdrop-blur-lg shadow-lg' : 'bg-transparent'
                    }`}
                initial={{ y: -100 }}
                animate={{ y: 0 }}
                transition={{ duration: 0.6, ease: smoothEase }}
            >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-20">
                        {/* Logo */}
                        <Link href="/" className="flex items-center gap-3 group">
                            <div className="w-11 h-11 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/25 group-hover:shadow-emerald-500/40 transition-shadow">
                                <span className="text-white font-bold text-xl">S</span>
                            </div>
                            <span className="text-2xl font-extrabold text-slate-800">
                                Santrix
                            </span>
                        </Link>

                        {/* Desktop Nav */}
                        <div className="hidden md:flex items-center gap-8">
                            {[
                                { label: 'Fitur', id: 'fitur' },
                                { label: 'Harga', id: 'harga' },
                                { label: 'Testimoni', id: 'testimoni' },
                                { label: 'Kontak', id: 'kontak' },
                            ].map((item) => (
                                <button
                                    key={item.id}
                                    onClick={() => scrollToSection(item.id)}
                                    className="text-slate-600 hover:text-emerald-600 font-medium transition-colors"
                                >
                                    {item.label}
                                </button>
                            ))}
                        </div>

                        {/* CTA Buttons */}
                        <div className="hidden md:flex items-center gap-4">
                            <Link
                                href="/login"
                                className="px-5 py-2.5 text-slate-600 hover:text-slate-900 font-semibold transition-colors"
                            >
                                Masuk
                            </Link>
                            <motion.div whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}>
                                <Link
                                    href="/register-pesantren"
                                    className="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all"
                                >
                                    Daftar Gratis
                                </Link>
                            </motion.div>
                        </div>

                        {/* Mobile Menu Button */}
                        <button
                            className="md:hidden p-2 text-slate-700"
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                        >
                            {mobileMenuOpen ? Icons.close : Icons.menu}
                        </button>
                    </div>
                </div>

                {/* Mobile Menu */}
                <AnimatePresence>
                    {mobileMenuOpen && (
                        <motion.div
                            initial={{ opacity: 0, height: 0 }}
                            animate={{ opacity: 1, height: 'auto' }}
                            exit={{ opacity: 0, height: 0 }}
                            transition={{ duration: 0.3 }}
                            className="md:hidden bg-white border-t shadow-lg"
                        >
                            <div className="px-4 py-6 space-y-4">
                                {['Fitur', 'Harga', 'Testimoni', 'Kontak'].map((item) => (
                                    <button
                                        key={item}
                                        onClick={() => scrollToSection(item.toLowerCase())}
                                        className="block w-full text-left px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-lg font-medium"
                                    >
                                        {item}
                                    </button>
                                ))}
                                <hr className="my-4" />
                                <Link
                                    href="/login"
                                    className="block w-full px-4 py-3 text-center text-slate-700 font-semibold"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    href="/register-pesantren"
                                    className="block w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-center font-semibold rounded-xl"
                                >
                                    Daftar Gratis
                                </Link>
                            </div>
                        </motion.div>
                    )}
                </AnimatePresence>
            </motion.nav>

            {/* Hero Section */}
            <section ref={heroRef} className="relative min-h-screen flex items-center pt-20 overflow-hidden bg-gradient-to-b from-slate-50 to-white">
                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-[0.02]">
                    <svg className="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" strokeWidth="1" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" className="text-slate-900" />
                    </svg>
                </div>

                {/* Gradient Orbs */}
                <div className="absolute top-20 left-10 w-72 h-72 bg-emerald-200 rounded-full blur-3xl opacity-30"></div>
                <div className="absolute bottom-20 right-10 w-96 h-96 bg-teal-200 rounded-full blur-3xl opacity-30"></div>

                <motion.div
                    className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20"
                    style={{ opacity: heroOpacity, scale: heroScale }}
                >
                    <motion.div
                        className="text-center max-w-4xl mx-auto"
                        variants={staggerContainer}
                        initial="hidden"
                        animate="visible"
                    >
                        {/* Badge */}
                        <motion.div
                            variants={fadeInUp}
                            className="inline-flex items-center gap-2 px-5 py-2 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 font-medium text-sm mb-8"
                        >
                            <span className="flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Digunakan {formatNumber(safeStats.totalPesantren)}+ Pesantren di Indonesia
                        </motion.div>

                        {/* Headline */}
                        <motion.h1
                            variants={fadeInUp}
                            className="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 leading-tight mb-6 tracking-tight"
                        >
                            Kelola Pesantren dengan
                            <span className="block gradient-text">Lebih Mudah & Modern</span>
                        </motion.h1>

                        <motion.p
                            variants={fadeInUp}
                            className="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed"
                        >
                            Platform manajemen pesantren terlengkap. Dari administrasi santri,
                            keuangan, hingga pendidikan — <strong>semua terintegrasi dalam satu sistem</strong>.
                        </motion.p>

                        {/* CTA Buttons */}
                        <motion.div
                            variants={fadeInUp}
                            className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16"
                        >
                            <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                                <Link
                                    href="/register-pesantren"
                                    className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-2xl shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all"
                                >
                                    Mulai Gratis Sekarang {Icons.arrow}
                                </Link>
                            </motion.div>
                            <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                                <Link
                                    href="/demo-start/sekretaris"
                                    className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-slate-700 font-bold rounded-2xl border-2 border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 shadow-lg transition-all"
                                >
                                    Coba Demo Gratis
                                </Link>
                            </motion.div>
                        </motion.div>

                        {/* Stats */}
                        <motion.div
                            variants={fadeInUp}
                            className="grid grid-cols-3 gap-6 max-w-lg mx-auto"
                        >
                            {[
                                { value: safeStats.totalPesantren, label: 'Pesantren', icon: Icons.mosque },
                                { value: safeStats.totalSantri, label: 'Santri', icon: Icons.users },
                                { value: safeStats.totalUsers, label: 'Pengguna', icon: Icons.shield },
                            ].map((stat, index) => (
                                <motion.div
                                    key={index}
                                    className="text-center p-4 bg-white rounded-2xl border border-slate-100 shadow-sm"
                                    whileHover={{ y: -4, boxShadow: '0 12px 24px -8px rgba(0,0,0,0.1)' }}
                                    transition={{ duration: 0.2 }}
                                >
                                    <div className="text-3xl sm:text-4xl font-black text-slate-900">
                                        {formatNumber(stat.value)}+
                                    </div>
                                    <div className="text-sm text-slate-500 font-medium">{stat.label}</div>
                                </motion.div>
                            ))}
                        </motion.div>
                    </motion.div>
                </motion.div>

                {/* Scroll Indicator */}
                <motion.div
                    className="absolute bottom-8 left-1/2 -translate-x-1/2"
                    animate={{ y: [0, 8, 0] }}
                    transition={{ duration: 1.5, repeat: Infinity, ease: 'easeInOut' }}
                >
                    <div className="w-6 h-10 border-2 border-slate-300 rounded-full flex justify-center pt-2">
                        <div className="w-1 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                </motion.div>
            </section>

            {/* Dashboard Preview */}
            <section className="py-24 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-full mb-4">
                            Preview Dashboard
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-slate-900 mb-4">
                            Interface Modern & Intuitif
                        </h2>
                        <p className="text-lg text-slate-600">
                            Dashboard yang bersih, mudah dipahami, dan powerful untuk mengelola seluruh aspek pesantren.
                        </p>
                    </motion.div>

                    <motion.div
                        className="relative max-w-5xl mx-auto"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                        variants={scaleIn}
                    >
                        <div className="relative bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-1 shadow-2xl shadow-emerald-500/20">
                            <div className="bg-slate-900 rounded-xl overflow-hidden">
                                {/* Browser Bar */}
                                <div className="bg-slate-800 px-4 py-3 flex items-center gap-3">
                                    <div className="flex gap-2">
                                        <div className="w-3 h-3 rounded-full bg-red-400"></div>
                                        <div className="w-3 h-3 rounded-full bg-amber-400"></div>
                                        <div className="w-3 h-3 rounded-full bg-emerald-400"></div>
                                    </div>
                                    <div className="flex-1 bg-slate-700/50 rounded-lg px-4 py-2 text-slate-400 text-sm text-center">
                                        pesantren.santrix.my.id/dashboard
                                    </div>
                                </div>

                                {/* Dashboard Content */}
                                <div className="bg-slate-100 p-6">
                                    <div className="flex items-center justify-between mb-6">
                                        <div>
                                            <h3 className="text-slate-900 font-bold text-lg">Dashboard Admin</h3>
                                            <p className="text-slate-500 text-sm">PP. Riyadlul Huda</p>
                                        </div>
                                    </div>

                                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                                        {[
                                            { label: 'Total Santri', value: '245', color: 'text-indigo-600' },
                                            { label: 'Pemasukan', value: 'Rp 45.2jt', color: 'text-emerald-600' },
                                            { label: 'Kehadiran', value: '98.5%', color: 'text-sky-600' },
                                            { label: 'Kelas Aktif', value: '15', color: 'text-amber-600' },
                                        ].map((stat, i) => (
                                            <motion.div
                                                key={i}
                                                className="bg-white rounded-xl p-4 shadow-sm"
                                                initial={{ opacity: 0, y: 20 }}
                                                whileInView={{ opacity: 1, y: 0 }}
                                                transition={{ delay: i * 0.1 }}
                                                viewport={{ once: true }}
                                            >
                                                <div className={`text-xl font-bold ${stat.color}`}>{stat.value}</div>
                                                <div className="text-xs text-slate-500">{stat.label}</div>
                                            </motion.div>
                                        ))}
                                    </div>

                                    <div className="grid grid-cols-3 gap-4">
                                        <div className="col-span-2 bg-white rounded-xl p-4 shadow-sm h-32">
                                            <div className="text-sm font-semibold text-slate-700 mb-3">Grafik Pembayaran SPP</div>
                                            <div className="flex items-end gap-1 h-16">
                                                {[40, 65, 45, 80, 55, 90, 70, 85, 60, 95, 75, 88].map((h, i) => (
                                                    <motion.div
                                                        key={i}
                                                        className="flex-1 bg-gradient-to-t from-emerald-500 to-teal-400 rounded-t"
                                                        initial={{ height: 0 }}
                                                        whileInView={{ height: `${h}%` }}
                                                        transition={{ delay: i * 0.05, duration: 0.5 }}
                                                        viewport={{ once: true }}
                                                    />
                                                ))}
                                            </div>
                                        </div>
                                        <div className="bg-white rounded-xl p-4 shadow-sm">
                                            <div className="text-sm font-semibold text-slate-700 mb-3">Aktivitas</div>
                                            <div className="space-y-2 text-xs text-slate-500">
                                                <div className="flex items-center gap-2">
                                                    <div className="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                                    Pembayaran SPP
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <div className="w-1.5 h-1.5 rounded-full bg-sky-500"></div>
                                                    Santri Baru
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </motion.div>
                </div>
            </section>

            {/* Features Section */}
            <section id="fitur" className="py-24 bg-slate-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-full mb-4">
                            Fitur Lengkap
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-slate-900 mb-4">
                            4 Dashboard Profesional
                        </h2>
                        <p className="text-lg text-slate-600">
                            Akses terpisah untuk setiap divisi. Lebih fokus, lebih aman, lebih produktif.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-2 gap-6"
                        variants={staggerContainer}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {dashboardFeatures.map((feature) => (
                            <motion.div
                                key={feature.title}
                                variants={fadeInUp}
                                whileHover={{ y: -6 }}
                                transition={{ duration: 0.2 }}
                            >
                                <Link
                                    href={feature.href}
                                    className="block bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-shadow p-8 group"
                                >
                                    <div className="flex items-start gap-5">
                                        <div className={`w-14 h-14 ${feature.color} rounded-xl flex items-center justify-center text-white shadow-lg`}>
                                            {feature.icon}
                                        </div>
                                        <div className="flex-1">
                                            <h3 className={`text-xl font-bold text-slate-800 mb-1 group-hover:${feature.textColor} transition-colors`}>
                                                {feature.title}
                                            </h3>
                                            <p className="text-slate-500 text-sm font-medium mb-3">
                                                {feature.subtitle}
                                            </p>
                                            <p className="text-slate-600 text-sm leading-relaxed mb-4">
                                                {feature.description}
                                            </p>
                                            <div className="flex flex-wrap gap-2">
                                                {feature.tags.map((tag) => (
                                                    <span key={tag} className={`px-3 py-1 ${feature.lightBg} text-xs font-medium ${feature.textColor} rounded-full`}>
                                                        {tag}
                                                    </span>
                                                ))}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
                                        <span className="text-sm text-slate-500">Klik untuk demo</span>
                                        <span className={`font-semibold ${feature.textColor} group-hover:translate-x-1 transition-transform inline-flex items-center gap-1`}>
                                            Masuk {Icons.arrow}
                                        </span>
                                    </div>
                                </Link>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* Pricing Section */}
            <section id="harga" className="py-24 bg-slate-900">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-emerald-500/20 text-emerald-400 text-sm font-semibold rounded-full mb-4 border border-emerald-500/30">
                            Harga Terjangkau
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-white mb-4">
                            Pilih Paket Terbaik
                        </h2>
                        <p className="text-lg text-slate-400">
                            Investasi kecil untuk kemudahan besar dalam mengelola pesantren.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-2 lg:grid-cols-4 gap-6"
                        variants={staggerContainer}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {packages.map((pkg, index) => (
                            <motion.div
                                key={pkg.id}
                                className={`relative bg-slate-800/50 backdrop-blur rounded-2xl border ${index === 1 ? 'border-emerald-500 ring-1 ring-emerald-500/50' : 'border-slate-700'} overflow-hidden`}
                                variants={fadeInUp}
                                whileHover={{ y: -8 }}
                                transition={{ duration: 0.2 }}
                            >
                                {index === 1 && (
                                    <div className="absolute top-0 left-0 right-0 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-bold text-center py-1.5">
                                        PALING POPULER
                                    </div>
                                )}
                                <div className={`p-6 ${index === 1 ? 'pt-10' : ''}`}>
                                    <div className="text-center mb-6">
                                        <h3 className="text-xl font-bold text-white mb-2">{pkg.name}</h3>
                                        <div className="text-3xl font-black text-emerald-400">
                                            {formatPrice(pkg.price)}
                                        </div>
                                        <div className="text-sm text-slate-400">/ {pkg.duration_months} bulan</div>
                                    </div>
                                    <ul className="space-y-3 mb-6">
                                        <li className="flex items-center gap-2 text-slate-300 text-sm">
                                            {Icons.check}
                                            Maks {pkg.max_santri.toLocaleString()} Santri
                                        </li>
                                        <li className="flex items-center gap-2 text-slate-300 text-sm">
                                            {Icons.check}
                                            Maks {pkg.max_users} User
                                        </li>
                                        {getFeatures(pkg.features).slice(0, 3).map((feature) => (
                                            <li key={feature} className="flex items-center gap-2 text-slate-300 text-sm">
                                                {Icons.check}
                                                {feature}
                                            </li>
                                        ))}
                                    </ul>
                                    <motion.div whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}>
                                        <Link
                                            href="/register-pesantren"
                                            className={`block w-full py-3 text-center font-bold rounded-xl transition-all ${index === 1
                                                    ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-500/25'
                                                    : 'bg-slate-700 text-white hover:bg-slate-600'
                                                }`}
                                        >
                                            Pilih Paket
                                        </Link>
                                    </motion.div>
                                </div>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* Testimonials */}
            <section id="testimoni" className="py-24 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-amber-50 text-amber-700 text-sm font-semibold rounded-full mb-4">
                            Testimoni
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-slate-900 mb-4">
                            Dipercaya Pesantren Nusantara
                        </h2>
                        <p className="text-lg text-slate-600">
                            Cerita sukses dari pesantren yang telah menggunakan Santrix.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-3 gap-8"
                        variants={staggerContainer}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {testimonials.map((t, index) => (
                            <motion.div
                                key={index}
                                className="bg-slate-50 rounded-2xl p-8 border border-slate-100"
                                variants={fadeInUp}
                                whileHover={{ y: -4 }}
                            >
                                <div className="flex gap-1 mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <span key={i}>{Icons.star}</span>
                                    ))}
                                </div>
                                <p className="text-slate-600 mb-6 leading-relaxed">"{t.text}"</p>
                                <div className="flex items-center gap-4">
                                    <div className="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {t.avatar}
                                    </div>
                                    <div>
                                        <div className="font-bold text-slate-900">{t.name}</div>
                                        <div className="text-sm text-slate-500">{t.role}</div>
                                    </div>
                                </div>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-24 bg-gradient-to-r from-emerald-500 to-teal-600">
                <motion.div
                    className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    variants={fadeInUp}
                >
                    <h2 className="text-3xl sm:text-4xl font-black text-white mb-6">
                        Siap Modernisasi Pesantren Anda?
                    </h2>
                    <p className="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                        Bergabung dengan ratusan pesantren yang telah merasakan kemudahan mengelola administrasi dengan Santrix.
                    </p>
                    <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                            <Link
                                href="/register-pesantren"
                                className="px-10 py-4 bg-white text-emerald-600 font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all inline-flex items-center gap-2"
                            >
                                Mulai Gratis Sekarang {Icons.arrow}
                            </Link>
                        </motion.div>
                        <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                            <a
                                href="https://wa.me/6281234567890"
                                className="px-10 py-4 bg-white/10 backdrop-blur text-white font-bold rounded-2xl border-2 border-white/30 hover:bg-white/20 transition-all"
                            >
                                Hubungi Kami
                            </a>
                        </motion.div>
                    </div>
                </motion.div>
            </section>

            {/* Footer */}
            <footer id="kontak" className="bg-slate-900 text-white pt-20 pb-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-4 gap-12 mb-12">
                        <div className="col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="w-11 h-11 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                                    <span className="text-white font-bold text-xl">S</span>
                                </div>
                                <span className="text-2xl font-bold">Santrix</span>
                            </div>
                            <p className="text-slate-400 max-w-md mb-6 leading-relaxed">
                                Sistem manajemen pesantren modern yang membantu ribuan pesantren di Indonesia mengelola administrasi dengan lebih efisien.
                            </p>
                        </div>
                        <div>
                            <h4 className="font-bold mb-6 text-lg">Produk</h4>
                            <ul className="space-y-3 text-slate-400">
                                <li><button onClick={() => scrollToSection('fitur')} className="hover:text-white transition-colors">Fitur</button></li>
                                <li><button onClick={() => scrollToSection('harga')} className="hover:text-white transition-colors">Harga</button></li>
                                <li><Link href="/demo-start/sekretaris" className="hover:text-white transition-colors">Demo</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold mb-6 text-lg">Kontak</h4>
                            <ul className="space-y-3 text-slate-400">
                                <li>support@santrix.my.id</li>
                                <li>+62 812-3456-7890</li>
                                <li>Indonesia</li>
                            </ul>
                        </div>
                    </div>

                    <div className="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                        <p className="text-slate-500">© 2025 Santrix. All rights reserved.</p>
                        <p className="text-slate-500 text-sm">Made with ❤️ for Pesantren Indonesia</p>
                    </div>
                </div>
            </footer>
        </>
    );
}
