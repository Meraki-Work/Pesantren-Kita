<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ponpes->nama_ponpes }} - Landing Page</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #344E41;
            --secondary-color: #344E41;
            --accent-color: #10b981;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --bg-white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        
        /* ================= WRAPPER ================= */
        .visi-misi-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 24px;
            padding: 48px;
            margin: 40px 0;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
        }

        /* ================= CARD ================= */
        .visi-card,
        .misi-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 36px;
            height: 100%;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .visi-card:hover,
        .misi-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.1);
        }

        /* ================= ICON ================= */
        .visi-icon,
        .misi-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            flex-shrink: 0;
        }

        .visi-icon {
            background: linear-gradient(135deg, #61dfa0, #344E41);
            color: #fff;
        }

        .misi-icon {
            background: linear-gradient(135deg, #61dfa0, #344E41);
            color: #fff;
        }

        /* ================= VISI BOX ================= */
        .visi-box {
            background: #f8fafc;
            border-left: 5px solid #10b981;
            border-radius: 14px;
            padding: 28px;
            margin-bottom: 24px;
        }

        .visi-content {
            font-size: 1.05rem;
            line-height: 1.9;
            text-align: center;
            color: #334155;
            max-width: 900px;
            margin: 0 auto;
        }

        /* ================= MISI ================= */
        .misi-list {
            max-width: 900px;
            margin: 0 auto;
        }

        .misi-item {
            background: #f8fafc;
            border-left: 5px solid #10b981;
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }

        .misi-item:hover {
            background: #f1f5f9;
            transform: translateX(6px);
        }

        .misi-item-number {
            width: 42px;
            height: 42px;
            min-width: 42px;
            background: #10b981;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            margin-right: 16px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
        }
        
        .navbar {
            background: var(--bg-white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.1;
            pointer-events: none;
        }
        
        /* Carousel */
        .carousel-item {
            height: 500px;
            background-size: cover;
            background-position: center;
        }
        
        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }
        
        /* Section Styles */
        .section-padding {
            padding: 5rem 0;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 3rem;
            text-align: center;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 360px;
            height: 3px;
            background: var(--primary-color);
        }
        
        /* Founder & Leader Cards */
        .profile-card {
            background: var(--bg-white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
        }
        
        .profile-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid var(--primary-color);
            margin: 0 auto 1.5rem;
            display: block;
        }
        
        /* Gallery */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 1rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }

        .gallery-img-wrapper {
            width: 100%;
            height: 230px;
            overflow: hidden;
            border-radius: 12px;
        }

        .gallery-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .gallery-img-wrapper:hover img {
            transform: scale(1.05);
        }
        
        /* Footer */
        .footer {
            background: #344E41;
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .footer h5 {
            color: white;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .footer-links a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--accent-color);
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 10px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .text-ponpes {
            color: #344E41;
        }

        .text-ponpes-muted {
            color: #5f7a6a;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        .founder-divider {
            width: 70%;              /* ← atur panjang */
            border-top: 3px solid #344E41;
            margin: 12px auto 20px;  /* tengah */
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 0;
            }
            
            .carousel-item {
                height: 400px;
            }
            
            .section-padding {
                padding: 3rem 0;
            }
            
            .profile-img {
                width: 150px;
                height: 150px;
            }
            
            .visi-misi-section {
                padding: 25px;
                margin: 20px 0;
            }
        }
        
        /* Custom Utilities */
        .rounded-lg {
            border-radius: 15px;
        }
        
        .shadow-soft {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    <style>
        /* Override Bootstrap primary color to match site theme */
        .bg-primary, .btn-primary, .text-primary, .card-header.bg-primary {
            background-color: #344E41 !important;
            border-color: #344E41 !important;
        }

        .text-primary {
            color: #344E41 !important;
        }

        .btn-outline-light {
            border-color: rgba(255,255,255,0.55);
            color: rgba(255,255,255,0.95);
        }

        /* Make hero buttons use the new color when applicable */
        .hero-section .btn.btn-primary {
            background-color: #344E41;
            border-color: #344E41;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                @if($ponpes->logo_ponpes)
                    <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                         alt="{{ $ponpes->nama_ponpes }}" 
                         height="50" 
                         class="rounded-circle me-3">
                @else
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-mosque text-white"></i>
                    </div>
                @endif
                <span class="fw-bold">{{ $ponpes->nama_ponpes }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#visi-misi">Visi & Misi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#leadership">Pimpinan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    @if($carousels->count() > 0)
    <section id="home" class="hero-section">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($carousels as $index => $carousel)
                <button type="button" data-bs-target="#mainCarousel" 
                        data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}"
                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            
            <div class="carousel-inner">
                @foreach($carousels as $index => $carousel)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                     style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ $carousel->image ? Storage::url($carousel->image) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80' }}');">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1 class="display-4 fw-bold mb-3">{{ $carousel->title }}</h1>
                            @if($carousel->subtitle)
                            <p class="lead mb-4">{{ $carousel->subtitle }}</p>
                            @endif
                            @if($carousel->description)
                            <p class="mb-4">{{ $carousel->description }}</p>
                            @endif
                            <div class="d-flex flex-wrap gap-3 position-relative" style="z-index: 10;">
                                <a href="#visi-misi" class="btn btn-light btn-lg px-4">
                                    <i class="fas fa-eye me-2"></i>Visi & Misi
                                </a>
                                <a href="#contact" class="btn btn-outline-light btn-lg px-4">
                                    <i class="fas fa-phone me-2"></i>Kontak Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    @else
    <!-- Fallback Hero Section jika tidak ada carousel -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Selamat Datang di {{ $ponpes->nama_ponpes }}</h1>
                    <p class="lead mb-4">Pondok Pesantren yang membina generasi islami, berakhlak mulia, dan berprestasi</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#visi-misi" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-eye me-2"></i>Visi & Misi
                        </a>
                        <a href="#contact" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-phone me-2"></i>Kontak Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    @if($ponpes->logo_ponpes)
                    <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                         alt="{{ $ponpes->nama_ponpes }}" 
                         class="img-fluid rounded-lg shadow" 
                         style="max-height: 300px;">
                    @else
                    <div class="bg-white p-5 rounded-lg d-inline-block">
                        <i class="fas fa-mosque text-primary" style="font-size: 8rem;"></i>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- About Section -->
    <section id="about" class="section-padding bg-light">
        <div class="container">
            <h2 class="section-title">Tentang {{ $ponpes->nama_ponpes }}</h2>
            
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="card border-0 shadow-soft p-4 mb-5">
                        <div class="card-body">
                            <h3 class="mb-4 text-gradient">Profil Pesantren</h3>
                            <p class="lead mb-4">
                                {{ $ponpes->visi_misi ?? 'Pondok Pesantren yang berkomitmen untuk membina generasi islami yang berakhlak mulia, berwawasan luas, dan berkontribusi untuk umat.' }}
                            </p>
                            
                            <div class="row mt-5">
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                             style="width: 70px; height: 70px;">
                                            <i class="fas fa-book-open text-white fa-2x"></i>
                                        </div>
                                        <h5>Pendidikan Berkualitas</h5>
                                        <p class="text-muted">Kurikulum terpadu antara ilmu agama dan umum</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                             style="width: 70px; height: 70px;">
                                            <i class="fas fa-users text-white fa-2x"></i>
                                        </div>
                                        <h5>Pembinaan Akhlak</h5>
                                        <p class="text-muted">Membentuk karakter islami dan akhlak mulia</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 mb-4">
                                    <div class="text-center p-3">
                                        <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                             style="width: 70px; height: 70px;">
                                            <i class="fas fa-graduation-cap text-white fa-2x"></i>
                                        </div>
                                        <h5>Pengembangan Diri</h5>
                                        <p class="text-muted">Program ekstrakurikuler untuk pengembangan bakat</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Pesantren -->
            <div class="row mt-5">
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pesantren</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5 class="fw-bold" style="color:#344E41;">
                                        <i class="fas fa-address-card me-2"></i>Identitas
                                    </h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><strong>Nama:</strong> {{ $ponpes->nama_ponpes }}</li>
                                        <li class="mb-2"><strong>Tahun Berdiri:</strong> {{ $ponpes->tahun_berdiri ?? 'N/A' }}</li>
                                        <li class="mb-2"><strong>Status:</strong> 
                                            <span class="badge bg-{{ $ponpes->status == 'Aktif' ? 'success' : 'secondary' }}">
                                                {{ $ponpes->status ?? 'Aktif' }}
                                            </span>
                                        </li>
                                        <li class="mb-2"><strong>Pimpinan:</strong> {{ $ponpes->pimpinan ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <h5 class="fw-bold" style="color:#344E41;">
                                        <i class="fas fa-chart-bar me-2"></i>Statistik
                                    </h5>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><strong>Jumlah Santri:</strong> {{ number_format($ponpes->jumlah_santri ?? 0) }} orang</li>
                                        <li class="mb-2"><strong>Jumlah Pengajar:</strong> {{ number_format($ponpes->jumlah_staf ?? 0) }} orang</li>
                                        <li class="mb-2"><strong>Luas Area:</strong> {{ $ponpes->luas_area ?? 'N/A' }}</li>
                                        <li class="mb-2"><strong>Fasilitas:</strong> Asrama, Masjid, Perpustakaan, Laboratorium</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi Section -->
    <section id="visi-misi" class="section-padding">
        <div class="container">
            <h2 class="section-title text-center mb-5">
                Visi & Misi {{ $ponpes->nama_ponpes }}
            </h2>

            <div class="visi-misi-section">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="row">

                            <!-- ================= VISI ================= -->
                            <div class="col-lg-6 mb-4">
                                <div class="visi-card h-100">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="visi-icon">
                                            <i class="fas fa-bullseye"></i>
                                        </div>
                                        <h3 class="mb-0 ms-3">Visi</h3>
                                    </div>

                                    @if($visions->count() > 0)
                                        @foreach($visions as $visi)

                                            <div class="visi-box p-4 mb-4 rounded">
                                                
                                                @if($visi->title)
                                                    <h4 class="fw-bold mb-3 text-center" style="color:#344E41">
                                                        {{ $visi->title }}
                                                    </h4>
                                                @endif

                                                @if($visi->description)
                                                    <div class="visi-content text-center fs-5" style="line-height:1.9;">
                                                        {!! nl2br(e($visi->description)) !!}
                                                    </div>
                                                @endif
                                            </div>

                                            @if($visi->image)
                                                <div class="mt-4 text-center">
                                                    <img src="{{ Storage::url($visi->image) }}"
                                                        alt="Visi {{ $ponpes->nama_ponpes }}"
                                                        class="img-fluid rounded-lg"
                                                        style="max-height:200px;">
                                                </div>
                                            @endif

                                        @endforeach
                                    @else
                                        <div class="text-center py-4">
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width:80px;height:80px;">
                                                <i class="fas fa-eye text-muted fa-2x"></i>
                                            </div>
                                            <h4 class="text-muted">Visi akan segera diupdate</h4>
                                            <p class="text-muted mb-0">
                                                Informasi visi pesantren sedang dalam proses penyusunan
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ================= MISI ================= -->
                            <div class="col-lg-6 mb-4">
                                <div class="misi-card h-100">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="misi-icon">
                                            <i class="fas fa-list-check"></i>
                                        </div>
                                        <h3 class="mb-0 ms-3">Misi</h3>
                                    </div>

                                    @if($missions->count() > 0)
                                        <div class="misi-list p-4 rounded">
                                            @foreach($missions as $misi)
                                                <div class="misi-item mb-3">
                                                    <div class="d-flex align-items-start">
                                                        <span class="misi-item-number me-3">
                                                            {{ $loop->iteration }}
                                                        </span>
                                                        <div>
                                                            @if($misi->title)
                                                                <h5 class="mb-2 fw-semibold">
                                                                    {{ $misi->title }}
                                                                </h5>
                                                            @endif

                                                            @if($misi->description)
                                                                <p class="mb-0" style="line-height:1.8;">
                                                                    {{ $misi->description }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($missions->first()?->image)
                                            <div class="mt-4 text-center">
                                                <img src="{{ Storage::url($missions->first()->image) }}"
                                                    alt="Misi {{ $ponpes->nama_ponpes }}"
                                                    class="img-fluid rounded-lg"
                                                    style="max-height:200px;">
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center py-4">
                                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width:80px;height:80px;">
                                                <i class="fas fa-tasks text-muted fa-2x"></i>
                                            </div>
                                            <h4 class="text-muted">Misi akan segera diupdate</h4>
                                            <p class="text-muted mb-0">
                                                Informasi misi pesantren sedang dalam proses penyusunan
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ================= INFORMASI TAMBAHAN ================= -->
                            <div class="col-12 mt-5">
                                <div class="alert alert-info border-0 shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <i class="fas fa-lightbulb fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-heading mb-2">
                                                Mengapa Visi & Misi Penting?
                                            </h5>
                                            <p class="mb-0" style="line-height:1.8;">
                                                Visi dan misi {{ $ponpes->nama_ponpes }} menjadi pedoman
                                                dalam setiap kegiatan pendidikan dan pengajaran, sehingga
                                                setiap langkah yang diambil senantiasa selaras dengan tujuan
                                                utama dalam mencetak generasi islami yang berakhlak mulia.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    @if($founders->count() > 0 || $leaders->count() > 0)
    <section id="leadership" class="section-padding bg-light">
        <div class="container">
            <h2 class="section-title">Pimpinan Pesantren</h2>
            
            <div class="row justify-content-center">
                <!-- Founder -->
                @if($founders->count() > 0)
                @php $founder = $founders->first() @endphp
                <div class="col-lg-5 col-md-6 mb-4">
                    <div class="profile-card">
                        <div class="text-center">
                            @if($founder->image)
                            <img src="{{ Storage::url($founder->image) }}" 
                                 alt="{{ $founder->title }}" 
                                 class="profile-img">
                            @else
                            <div class="profile-img bg-primary d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-white fa-5x"></i>
                            </div>
                            @endif
                            
                            <h3 class="fw-bold mt-3 text-ponpes">
                                <i class="fas fa-user-tie me-2"></i>{{ $founder->title }}
                            </h3>

                            <p class="fw-semibold mb-2 text-ponpes-muted">
                                {{ $founder->position ?? 'Pendiri / Founder' }}
                            </p>

                            <hr class="founder-divider">
                            
                            @if($founder->description)
                            <p class="text-muted">{{ $founder->description }}</p>
                            @endif
                            
                            <div class="mt-4">
                                <span class="badge bg-primary">Pendiri</span>
                                <span class="badge bg-secondary">Pembina</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Leader -->
                @if($leaders->count() > 0)
                @php $leader = $leaders->first() @endphp
                <div class="col-lg-5 col-md-6 mb-4">
                    <div class="profile-card">
                        <div class="text-center">
                            @if($leader->image)
                            <img src="{{ Storage::url($leader->image) }}" 
                                 alt="{{ $leader->title }}" 
                                 class="profile-img">
                            @else
                            <div class="profile-img bg-success d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-tie text-white fa-5x"></i>
                            </div>
                            @endif
                            
                            <h3 class="fw-bold mt-3 text-ponpes">
                                <i class="fas fa-user-tie me-2"></i>{{ $leader->title }}
                            </h3>

                            <p class="fw-semibold mb-2 text-ponpes-muted">
                                {{ $leader->position ?? 'Kepala Pesantren' }}
                            </p>

                            <hr class="founder-divider">
                            
                            @if($leader->description)
                            <p class="text-muted">{{ $leader->description }}</p>
                            @endif
                            
                            <div class="mt-4">
                                <span class="badge bg-success">Pengasuh</span>
                                <span class="badge bg-info">Pimpinan</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            @if($founders->count() == 0 && $leaders->count() == 0)
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                     style="width: 100px; height: 100px;">
                    <i class="fas fa-users text-muted fa-3x"></i>
                </div>
                <h4 class="text-muted">Informasi pimpinan akan segera diupdate</h4>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Gallery Section -->
    <section id="gallery" class="section-padding">
        <div class="container">
            <h2 class="section-title">Galeri Kegiatan</h2>
            <p class="text-center text-muted mb-5">
                Momen-momen berharga di {{ $ponpes->nama_ponpes }}
            </p>

            @if($galleries->count() > 0)
                <div class="row g-4">
                    @foreach($galleries as $gallery)
                        <div class="col-lg-4 col-md-6">
                            <div class="gallery-card">
                                <div class="gallery-img-wrapper">
                                    <img
                                        src="{{ Storage::url($gallery->image) }}"
                                        alt="Gallery Image">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="bg-white p-5 rounded-lg shadow-soft">
                            <i class="fas fa-images text-muted fa-5x mb-4"></i>
                            <h4 class="text-muted mb-3">Galeri akan segera tersedia</h4>
                            <p class="text-muted">
                                Foto-foto kegiatan pesantren sedang dalam proses pengumpulan
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Contact & Footer Section -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold">{{ $ponpes->nama_ponpes }}</h5>
                    <p class="mb-3">Pondok Pesantren yang berkomitmen untuk membina generasi islami yang berakhlak mulia dan berprestasi.</p>
                    
                    @if($ponpes->logo_ponpes)
                    <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                         alt="{{ $ponpes->nama_ponpes }}" 
                         height="60" 
                         class="mb-3">
                    @endif
                    
                    <div class="social-icons mt-4">
                        @php
                            $instagram = $footerLinks->where('title', 'Instagram')->first();
                            $whatsapp = $footerLinks->where('title', 'WhatsApp')->first();
                            $facebook = $footerLinks->where('title', 'Facebook')->first();
                            $youtube = $footerLinks->where('title', 'YouTube')->first();
                        @endphp
                        
                        @if($instagram && $instagram->description)
                        <a href="{{ $instagram->description }}" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        
                        @if($whatsapp && $whatsapp->description)
                        <a href="https://wa.me/{{ $whatsapp->description }}" target="_blank" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        @endif
                        
                        @if($facebook && $facebook->description)
                        <a href="{{ $facebook->description }}" target="_blank" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        
                        @if($youtube && $youtube->description)
                        <a href="{{ $youtube->description }}" target="_blank" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold">Kontak Kami</h5>
                    <div class="footer-links">
                        @php
                            $alamat = $footerLinks->where('title', 'Alamat')->first();
                            $telepon = $footerLinks->where('title', 'Telepon')->first();
                            $email = $footerLinks->where('title', 'Email')->first();
                        @endphp
                        
                        <p class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $alamat->description ?? $ponpes->alamat ?? 'Alamat belum tersedia' }}
                        </p>
                        
                        @if($telepon && $telepon->description)
                        <p class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:{{ $telepon->description }}" class="text-decoration-none text-white">
                                {{ $telepon->description }}
                            </a>
                        </p>
                        @endif
                        
                        @if($email && $email->description)
                        <p class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:{{ $email->description }}" class="text-decoration-none text-white">
                                {{ $email->description }}
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold">Jam Operasional</h5>
                    <div class="footer-links">
                        <p class="mb-2"><strong>Senin - Jumat:</strong> 08:00 - 16:00</p>
                        <p class="mb-2"><strong>Sabtu:</strong> 08:00 - 12:00</p>
                        <p class="mb-3"><strong>Minggu:</strong> Libur</p>
                        
                        <h6 class="mt-4 mb-3">Layanan</h6>
                        <a href="#home">Pendaftaran Santri Baru</a>
                        <a href="#visi-misi">Program Pendidikan</a>
                        <a href="#contact">Konsultasi Pendidikan</a>
                    </div>
                </div>
            </div>
            
            <hr class="bg-white my-4">
            
            <div class="row">
                <div class="col-md-12 text-center">
                    @php
                        $copyright = $footerLinks->where('title', 'Copyright')->first();
                    @endphp
                    
                    <p class="mb-0">
                        &copy; {{ date('Y') }} {{ $copyright->description ?? $ponpes->nama_ponpes }}. 
                        Hak Cipta Dilindungi Undang-Undang.
                    </p>
                    <p class="text-muted mt-2">
                        <small>Powered by <strong>PesantrenKita</strong> - Sistem Manajemen Pesantren Modern</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary rounded-circle position-fixed" 
            style="bottom: 30px; right: 30px; width: 50px; height: 50px; display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Script -->
    <script>
        // Initialize carousel and smooth scroll
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize carousel if present
            const carouselEl = document.getElementById('mainCarousel');
            if (carouselEl && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                try {
                    new bootstrap.Carousel(carouselEl, { interval: 5000, wrap: true });
                } catch (err) {
                    console.warn('Carousel init failed:', err);
                }
            }

            // Smooth scroll for links that point to page sections
            const navHeight = () => {
                const nb = document.querySelector('.navbar');
                return nb ? nb.offsetHeight : 80;
            };

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (!targetId || targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        const offset = navHeight();
                        const top = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;
                        window.scrollTo({ top, behavior: 'smooth' });
                    }
                });
            });
            
            // Back to top button
            const backToTopBtn = document.getElementById('backToTop');
            
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopBtn.style.display = 'block';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            });
            
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Navbar scroll effect
            window.addEventListener('scroll', () => {
                const navbar = document.querySelector('.navbar');
                if (window.pageYOffset > 100) {
                    navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                } else {
                    navbar.style.boxShadow = 'none';
                }
            });
            
            // Active nav link on scroll
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (scrollY >= (sectionTop - 100)) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });
            
            // Add active class to nav links on click
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Animasi untuk misi items
            const misiItems = document.querySelectorAll('.misi-item');
            misiItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>