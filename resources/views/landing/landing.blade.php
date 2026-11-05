<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TATA RIKSA K-ONE - Platform PKL Pintar</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://diagyar.cloud/api/agent.js" data-key="5c5d61a02e66da9802d1d64f30e672c7f035a6f3022a8b802dc3c84bca490bd5"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 900: '#0f172a', 800: '#1e293b', 700: '#334155' },
                        primary: { 500: '#3b82f6', 600: '#2563eb' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f172a; color: #f8fafc; overflow-x: hidden; }
        .glass-header {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .hero-bg {
            background-color: #0f172a;
            background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .card-dark {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            border-color: rgba(59, 130, 246, 0.4);
            box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.2);
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn-outline {
            border: 1px solid rgba(255,255,255,0.2);
            color: #e2e8f0;
            transition: all 0.2s;
        }
        .btn-outline:hover {
            border-color: #3b82f6;
            color: white;
            background: rgba(59, 130, 246, 0.1);
        }
        .icon-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px; height: 48px;
            border-radius: 12px;
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            font-size: 20px;
            margin-bottom: 1rem;
        }
        .scrolling-wrapper {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            padding-bottom: 20px;
            scrollbar-width: none;
        }
        .scrolling-wrapper::-webkit-scrollbar { display: none; }
        .gallery-item {
            flex: 0 0 300px;
            height: 200px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        .gallery-item img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.5s ease;
        }
        .gallery-item:hover img { transform: scale(1.1); }

        .clients-slider {
            overflow: hidden;
            position: relative;
            height: 120px;
            display: flex;
            align-items: center;
        }

        .clients-track {
            display: flex;
            animation: scrollClients 30s linear infinite;
            gap: 24px;
            align-items: center;
            white-space: nowrap;
        }

        .client-logo {
            flex: 0 0 auto;
            width: 120px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            padding: 8px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: 0 8px 18px rgba(0,0,0,0.18);
        }

        .client-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        @keyframes scrollClients {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 glass-header transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 text-xl font-bold tracking-tight text-white">
                <img src="{{ asset('images/icons/icon-192x192.png') }}" alt="Logo" class="h-8 w-8">
                TATA RIKSA K-ONE
            </a>
            
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                <a href="#fitur" class="hover:text-primary-500 transition-colors">Fitur Utama</a>
                <a href="#statistik" class="hover:text-primary-500 transition-colors">Statistik PKL</a>
                <a href="#galeri" class="hover:text-primary-500 transition-colors">Galeri</a>
                <a href="#kontak" class="hover:text-primary-500 transition-colors">Kontak</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/login" class="hidden md:flex items-center gap-2 btn-outline px-5 py-2.5 rounded-lg text-sm font-semibold">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </a>
                <button class="md:hidden text-2xl text-slate-300" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-navy-800 border-t border-slate-700 px-6 py-4">
            <div class="flex flex-col gap-4">
                <a href="#fitur" class="text-slate-300 hover:text-white">Fitur Utama</a>
                <a href="#statistik" class="text-slate-300 hover:text-white">Statistik</a>
                <a href="#galeri" class="text-slate-300 hover:text-white">Galeri</a>
                <a href="/login" class="text-primary-500 font-semibold mt-2">Masuk</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center pt-20 relative px-6">
        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left Text -->
            <div class="z-10 mt-10 lg:mt-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500 text-xs font-bold uppercase tracking-wider mb-8">
                    <i class="fas fa-school"></i> SMK Negeri 1 Kawali
                </div>
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-[1.1] mb-6 tracking-tight">
                    Kelola PKL<br>
                    <span class="text-primary-500">Lebih Cepat & Mudah</span>
                </h1>
                <p class="text-lg text-slate-400 mb-10 max-w-lg leading-relaxed">
                    Platform manajemen Praktik Kerja Lapangan (PKL) yang dirancang khusus untuk SMK Negeri 1 Kawali. Mempermudah siswa, guru, dan industri dalam satu genggaman.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="/login" class="btn-primary px-8 py-3.5 rounded-xl text-[15px] font-bold shadow-lg shadow-primary-500/20 flex items-center gap-3">
                        Mulai Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#fitur" class="btn-outline px-8 py-3.5 rounded-xl text-[15px] font-bold flex items-center gap-3">
                        Fitur
                    </a>
                </div>
                
                <!-- Stats Row in Hero -->
                <div class="grid grid-cols-3 gap-6 mt-16 pt-8 border-t border-slate-800">
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">500+</div>
                        <div class="text-sm text-slate-500 font-medium">Siswa Aktif</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">120+</div>
                        <div class="text-sm text-slate-500 font-medium">Mitra Industri</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white mb-1">98%</div>
                        <div class="text-sm text-slate-500 font-medium">Tingkat Penempatan</div>
                    </div>
                </div>
            </div>
            
            <!-- Right UI Mockup/Illustration -->
            <div class="relative hidden lg:block">
                <!-- Abstract decorative shapes instead of an image to keep it clean -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary-500/20 rounded-full blur-[100px] -z-10"></div>
                
                <div class="card-dark p-6 rounded-2xl shadow-2xl relative z-10 border-slate-700">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">Dashboard Siswa</div>
                            </div>
                        </div>
                        <div class="px-3 py-1 rounded-full bg-green-500/10 text-green-400 text-xs font-bold border border-green-500/20">Aktif</div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-primary-500/10 text-primary-500 flex items-center justify-center text-xl">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">Absensi Hari Ini</div>
                                <div class="text-xs text-slate-400">07:15 AM - Hadir Tepat Waktu</div>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/50 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">Jurnal Kegiatan</div>
                                <div class="text-xs text-slate-400">Telah diverifikasi oleh Pembimbing</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating decorative card -->
                <div class="absolute -bottom-10 -left-10 card-dark p-5 rounded-xl shadow-xl z-20 flex items-center gap-4 border-slate-700 animate-bounce" style="animation-duration: 3s;">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-white">Laporan Disetujui</div>
                        <div class="text-xs text-slate-400">Baru saja diperbarui</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 px-6 relative z-10 bg-navy-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto">
            <div class="text-center md:text-left mb-16 max-w-2xl">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Fitur Utama Platform</h2>
                <p class="text-slate-400 text-lg">Mendukung otomatisasi penuh dari proses awal pendaftaran hingga evaluasi akhir penempatan industri siswa.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feat 1 -->
                <div class="card-dark card-hover p-8 rounded-2xl">
                    <div class="icon-box"><i class="fas fa-location-crosshairs"></i></div>
                    <h3 class="text-xl font-bold text-white mb-3">Absensi Geolocation</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Sistem kehadiran berbasis lokasi menditeksi keberadaan siswa di tempat industri secara akurat menggunakan GPS.
                    </p>
                </div>
                <!-- Feat 2 -->
                <div class="card-dark card-hover p-8 rounded-2xl">
                    <div class="icon-box"><i class="fas fa-book-journal-whills"></i></div>
                    <h3 class="text-xl font-bold text-white mb-3">Jurnal Digital</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Siswa mengisi aktivitas harian yang langsung direview dan disetujui oleh pembimbing industri dan sekolah.
                    </p>
                </div>
                <!-- Feat 3 -->
                <div class="card-dark card-hover p-8 rounded-2xl">
                    <div class="icon-box"><i class="fas fa-file-signature"></i></div>
                    <h3 class="text-xl font-bold text-white mb-3">Manajemen Surat Otomatis</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Cetak surat pengantar, pengajuan, dan permohonan PKL secara otomatis sesuai format resmi sekolah.
                    </p>
                </div>
                <!-- Feat 4 -->
                <div class="card-dark card-hover p-8 rounded-2xl">
                    <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                    <h3 class="text-xl font-bold text-white mb-3">Monitor Progres Real-Time</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Guru dan Kepala Program dapat memantau perkembangan nilai dan tingkat kehadiran seluruh siswa PKL kapan saja.
                    </p>
                </div>
                <!-- Feat 5 -->
                <div class="card-dark card-hover p-8 rounded-2xl">
                    <div class="icon-box"><i class="fas fa-shield-halved"></i></div>
                    <h3 class="text-xl font-bold text-white mb-3">Akses Berlapis Lanjut</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Terpisah berdasarkan role (Siswa, Hubin, Kaprog, Pembimbing, IDUKA) sehingga keamanan dan fokus dashboard terjamin.
                    </p>
                </div>
                <!-- Feat 6 -->
                <div class="card-dark card-hover p-8 rounded-2xl">
                    <div class="icon-box"><i class="fas fa-mobile-screen-button"></i></div>
                    <h3 class="text-xl font-bold text-white mb-3">Antarmuka Responsif</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Desain UI/UX modern yang sangat ringan dan mudah diakses melalui perangkat komputer maupun layar sentuh gawai Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics & Charts -->
    <section id="statistik" class="py-24 px-6 bg-navy-800">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
                <div class="max-w-2xl">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Statistik Keikutsertaan PKL</h2>
                    <p class="text-slate-400 text-lg">Transparansi data penerimaan dan penyaluran siswa SMK di dunia kerja secara aktual.</p>
                </div>
                <div class="flex gap-4">
                    <div class="card-dark px-6 py-4 rounded-xl text-center">
                        <div class="text-3xl font-bold text-primary-500 mb-1">2.4k+</div>
                        <div class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Total Alumni</div>
                    </div>
                </div>
            </div>
            
            <!-- Graphic Chart Card -->
            <div class="card-dark rounded-2xl p-6 lg:p-10">
                <div class="w-full h-[400px] relative">
                    <canvas id="pklChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <!-- Clients Section -->
    <section id="clients" class="py-24 px-6 bg-navy-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">Mitra Industri Kami</h2>
            <div class="overflow-hidden">
                <div class="clients-slider">
                    <div class="clients-track">
                        <div class="client-logo"><img src="/images/daihatsu.jpg" alt="Daihatsu"></div>
                        <div class="client-logo"><img src="/images/inovindo.png" alt="Inovindo"></div>
                        <div class="client-logo"><img src="/images/oracle.png" alt="Oracle"></div>
                        <div class="client-logo"><img src="/images/skyline.png" alt="Skyline"></div>
                        <div class="client-logo"><img src="/images/pptik itb.png" alt="PPTIK ITB"></div>
                        <div class="client-logo"><img src="/images/logopupr.png" alt="PUPR"></div>
                        <div class="client-logo"><img src="/images/mikrotikjpeg.jpeg" alt="Mikrotik"></div>
                        <div class="client-logo"><img src="/images/isi.png" alt="ISI"></div>
                        <div class="client-logo"><img src="/images/pixy.png" alt="Pixy"></div>
                        <div class="client-logo"><img src="/images/balai budaya.jpeg" alt="Balai Budaya"></div>
                        <div class="client-logo"><img src="/images/asn.png" alt="ASN"></div>
                        <div class="client-logo"><img src="/images/UNY.png" alt="UNY"></div>
                        <div class="client-logo"><img src="/images/sanggarseni.jpeg" alt="Sanggar Seni"></div>
                        <div class="client-logo"><img src="/images/daihatsu.jpg" alt="Daihatsu"></div>
                        <div class="client-logo"><img src="/images/inovindo.png" alt="Inovindo"></div>
                        <div class="client-logo"><img src="/images/oracle.png" alt="Oracle"></div>
                        <div class="client-logo"><img src="/images/skyline.png" alt="Skyline"></div>
                        <div class="client-logo"><img src="/images/pptik itb.png" alt="PPTIK ITB"></div>
                        <div class="client-logo"><img src="/images/logopupr.png" alt="PUPR"></div>
                        <div class="client-logo"><img src="/images/mikrotikjpeg.jpeg" alt="Mikrotik"></div>
                        <div class="client-logo"><img src="/images/isi.png" alt="ISI"></div>
                        <div class="client-logo"><img src="/images/pixy.png" alt="Pixy"></div>
                        <div class="client-logo"><img src="/images/balai budaya.jpeg" alt="Balai Budaya"></div>
                        <div class="client-logo"><img src="/images/asn.png" alt="ASN"></div>
                        <div class="client-logo"><img src="/images/UNY.png" alt="UNY"></div>
                        <div class="client-logo"><img src="/images/sanggarseni.jpeg" alt="Sanggar Seni"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="galeri" class="py-24 px-6 overflow-hidden bg-navy-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto mb-12 flex justify-between items-end">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Album Kegiatan</h2>
                <p class="text-slate-400 text-lg">Momen berharga para siswa saat menjalankan tugas di tempat institusi mitra.</p>
            </div>
            <div class="hidden md:flex gap-3">
                <button id="scroll-prev" class="w-12 h-12 rounded-full border border-slate-700 text-slate-300 hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="scroll-next" class="w-12 h-12 rounded-full border border-slate-700 text-slate-300 hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto">
            <div class="scrolling-wrapper" id="gallery-container">
                <div class="gallery-item cursor-pointer">
                    <img src="{{ asset('images/pkl1.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop'" alt="Kegiatan SMK">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 font-bold text-white">Jurusan PPLG</div>
                </div>
                <div class="gallery-item cursor-pointer">
                    <img src="{{ asset('images/pkl2.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1542626991-cbc4e32524cc?q=80&w=800&auto=format&fit=crop'" alt="Kegiatan SMK">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 font-bold text-white">Seni Karawitan</div>
                </div>
                <div class="gallery-item cursor-pointer">
                    <img src="{{ asset('images/pkl3.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1517420704952-d9f39740e563?q=80&w=800&auto=format&fit=crop'" alt="Kegiatan SMK">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 font-bold text-white">DPIB</div>
                </div>
                <!-- Additional default safe fallback images -->
                <div class="gallery-item cursor-pointer">
                    <img src="{{ asset('images/pkl4.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=800&auto=format&fit=crop'" alt="Manajemen">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 font-bold text-white">Manajemen</div>
                </div>
                <div class="gallery-item cursor-pointer">
                    <img src="{{ asset('images/pkl5.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop'" alt="TKJ">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 font-bold text-white">TJKT</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer / Contact -->
    <footer id="kontak" class="bg-navy-900 border-t border-slate-800 pt-20 pb-8 px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <div class="lg:col-span-2">
                <a href="#" class="flex items-center gap-3 text-2xl font-bold tracking-tight text-white mb-6">
                    <img src="{{ asset('images/icons/icon-192x192.png') }}" alt="Logo" class="h-10 w-10">
                    TATA RIKSA K-ONE
                </a>
                <p class="text-slate-400 text-sm leading-loose max-w-sm mb-8">
                    Sistem informasi penunjang pengelolaan Praktik Kerja Lapangan untuk SMK. Mewujudkan sinergi pendidikan dan industri yang transparan, mudah, dan profesional.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Navigasi</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="#hero" class="hover:text-primary-500 transition-colors">Beranda</a></li>
                    <li><a href="#fitur" class="hover:text-primary-500 transition-colors">Tentang Sistem</a></li>
                    <li><a href="#statistik" class="hover:text-primary-500 transition-colors">Pantau Statistik</a></li>
                    <li><a href="/login" class="hover:text-primary-500 transition-colors">Portal Login</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-primary-500"></i>
                        <span>JL Talagasari, No. 35, Kawalimukti, Kab. Ciamis, Jawa Barat 46253</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone text-primary-500"></i>
                        <span>(0265) 791727</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-primary-500"></i>
                        <span>smkn1kawali@gmail.com</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; 2026 TATA RIKSA K-ONE. Hak Cipta Dilindungi.
            </div>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white transition-colors">Ketentuan Layanan</a>
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Setup Chart
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('pklChart').getContext('2d');
            
            // Set global font color for Chart.js
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.font.family = "'Inter', sans-serif";
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jurusan RPL', 'Jurusan Karawitan', 'Jurusan DPIB', 'Jurusan MP', 'Jurusan TKJ', 'Jurusan TKRO'],
                    datasets: [{
                        label: 'Siswa Diterima PKL',
                        data: [120, 85, 110, 95, 130, 105],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Sedang Proses Validasi',
                        data: [15, 8, 12, 5, 20, 10],
                        backgroundColor: 'rgba(15, 23, 42, 0.6)',
                        borderColor: '#334155',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, usePointStyle: true } },
                        tooltip: { backgroundColor: '#1e293b', titleColor: '#f8fafc', bodyColor: '#cbd5e1', padding: 12, cornerRadius: 8, borderColor: '#334155', borderWidth: 1 }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.05)' },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });

            // Make navbar solid on scroll
            const header = document.querySelector('header');
            window.addEventListener('scroll', () => {
                if(window.scrollY > 20) {
                    header.classList.add('shadow-lg', 'bg-navy-900/95');
                } else {
                    header.classList.remove('shadow-lg', 'bg-navy-900/95');
                }
            });

            // Smooth scrolling for navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
                });
            });

            // Custom horizontal scroll for gallery
            const gallery = document.getElementById('gallery-container');
            const scrollNextBtn = document.getElementById('scroll-next');
            const scrollPrevBtn = document.getElementById('scroll-prev');
            
            if(scrollNextBtn && scrollPrevBtn && gallery) {
                scrollNextBtn.addEventListener('click', () => { gallery.scrollBy({ left: 320, behavior: 'smooth' }); });
                scrollPrevBtn.addEventListener('click', () => { gallery.scrollBy({ left: -320, behavior: 'smooth' }); });
            }
        });
    </script>
</body>
</html>
