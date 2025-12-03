<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ponpes->nama_ponpes }} - Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .carousel-item {
            height: 500px;
            background-size: cover;
            background-position: center;
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
        }
        .founder-leader-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .gallery-img {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .gallery-img:hover {
            transform: scale(1.05);
        }
        .section-title {
            position: relative;
            margin-bottom: 40px;
        }
        .section-title:after {
            content: '';
            position: absolute;
            width: 100px;
            height: 3px;
            background: #0d6efd;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .contact-info i {
            width: 30px;
            color: #0d6efd;
        }
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin: 0 5px;
            transition: all 0.3s;
        }
        .social-icons a:hover {
            background: #0b5ed7;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                @if($ponpes->logo_ponpes)
                    <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                         alt="Logo {{ $ponpes->nama_ponpes }}" 
                         height="50" 
                         class="me-2 rounded-circle">
                @else
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                         style="width: 50px; height: 50px;">
                        <i class="bi bi-house-door-fill text-white"></i>
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
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
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

    <!-- Carousel -->
    @if($carousels->count() > 0)
    <section id="home">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($carousels as $index => $carousel)
                <button type="button" data-bs-target="#mainCarousel" 
                        data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            
            <div class="carousel-inner">
                @foreach($carousels as $index => $carousel)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}"
                     style="background-image: url('{{ $carousel->image ? Storage::url($carousel->image) : 'https://via.placeholder.com/1200x500/0d6efd/ffffff?text=' . urlencode($carousel->title) }}');">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-5 fw-bold">{{ $carousel->title }}</h2>
                        @if($carousel->subtitle)
                        <p class="lead">{{ $carousel->subtitle }}</p>
                        @endif
                        @if($carousel->description)
                        <p class="mb-0">{{ $carousel->description }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>
    @endif

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Tentang Pesantren</h2>
            
            <div class="row mb-5">
                @if($founder || $leader)
                <div class="col-md-12 mb-5">
                    <h4 class="text-center mb-4">Pimpinan Pesantren</h4>
                    <div class="row justify-content-center">
                        @if($founder)
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <div class="p-4 rounded shadow-sm bg-white">
                                @if($founder->image)
                                <img src="{{ Storage::url($founder->image) }}" 
                                     alt="{{ $founder->title }}" 
                                     class="founder-leader-img mb-3">
                                @else
                                <div class="founder-leader-img mb-3 mx-auto bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill text-muted" style="font-size: 80px;"></i>
                                </div>
                                @endif
                                <h4 class="fw-bold">{{ $founder->title }}</h4>
                                <p class="text-primary fw-bold">{{ $founder->position }}</p>
                                @if($founder->description)
                                <p class="text-muted">{{ $founder->description }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($leader)
                        <div class="col-md-5 text-center">
                            <div class="p-4 rounded shadow-sm bg-white">
                                @if($leader->image)
                                <img src="{{ Storage::url($leader->image) }}" 
                                     alt="{{ $leader->title }}" 
                                     class="founder-leader-img mb-3">
                                @else
                                <div class="founder-leader-img mb-3 mx-auto bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill text-muted" style="font-size: 80px;"></i>
                                </div>
                                @endif
                                <h4 class="fw-bold">{{ $leader->title }}</h4>
                                <p class="text-primary fw-bold">{{ $leader->position }}</p>
                                @if($leader->description)
                                <p class="text-muted">{{ $leader->description }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Pesantren Info -->
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Pesantren</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <h5 class="text-primary">Identitas</h5>
                                    <p><strong>Nama:</strong> {{ $ponpes->nama_ponpes }}</p>
                                    <p><strong>Tahun Berdiri:</strong> {{ $ponpes->tahun_berdiri }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-{{ $ponpes->status == 'Aktif' ? 'success' : 'danger' }}">
                                            {{ $ponpes->status }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <h5 class="text-primary">Kontak</h5>
                                    <p><strong>Alamat:</strong> {{ $ponpes->alamat }}</p>
                                    <p><strong>Telepon:</strong> {{ $ponpes->telp }}</p>
                                    <p><strong>Email:</strong> {{ $ponpes->email }}</p>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <h5 class="text-primary">Statistik</h5>
                                    <p><strong>Jumlah Santri:</strong> {{ number_format($ponpes->jumlah_santri) }} orang</p>
                                    <p><strong>Jumlah Staf:</strong> {{ number_format($ponpes->jumlah_staf) }} orang</p>
                                    <p><strong>Pimpinan:</strong> {{ $ponpes->pimpinan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    @if($gallery->count() > 0)
    <section id="gallery" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 section-title">Galeri Kegiatan</h2>
            <div class="row g-4">
                @foreach($gallery as $image)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="position-relative overflow-hidden">
                            <img src="{{ Storage::url($image->path_gambar) }}" 
                                 class="card-img-top gallery-img" 
                                 alt="{{ $image->keterangan ?? 'Gambar kegiatan' }}"
                                 loading="lazy">
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-primary">Foto</span>
                            </div>
                        </div>
                        @if($image->keterangan)
                        <div class="card-body">
                            <p class="card-text text-muted">
                                <small>{{ Str::limit($image->keterangan, 80) }}</small>
                            </p>
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $image->created_at->format('d M Y') }}
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($gallery->count() > 8)
            <div class="text-center mt-4">
                <button class="btn btn-outline-primary" id="loadMoreGallery">
                    <i class="bi bi-plus-circle me-1"></i>Load More
                </button>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer id="contact" class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        @if($ponpes->logo_ponpes)
                        <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                             alt="Logo" 
                             height="70" 
                             class="me-3 rounded-circle">
                        @endif
                        <div>
                            <h4 class="fw-bold mb-0">{{ $ponpes->nama_ponpes }}</h4>
                            <p class="text-muted mb-0">Pondok Pesantren Terpadu</p>
                        </div>
                    </div>
                    <p class="text-light">{{ $ponpes->alamat }}</p>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-4">Kontak Kami</h5>
                    <div class="contact-info">
                        @foreach($footerData as $item)
                            @if($item->title == 'Alamat' && $item->description)
                            <p class="mb-3">
                                <i class="bi bi-geo-alt"></i>
                                <span>{{ $item->description }}</span>
                            </p>
                            @endif
                            
                            @if($item->title == 'Email' && $item->description)
                            <p class="mb-3">
                                <i class="bi bi-envelope"></i>
                                <a href="mailto:{{ $item->description }}" class="text-white text-decoration-none">
                                    {{ $item->description }}
                                </a>
                            </p>
                            @endif
                            
                            @if($item->title == 'Telepon' && $item->description)
                            <p class="mb-3">
                                <i class="bi bi-telephone"></i>
                                <a href="tel:{{ $item->description }}" class="text-white text-decoration-none">
                                    {{ $item->description }}
                                </a>
                            </p>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-4">Sosial Media</h5>
                    <div class="social-icons mb-4">
                        @foreach($footerData as $item)
                            @if($item->title == 'Instagram' && $item->url)
                            <a href="{{ $item->url }}" target="_blank" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            @endif
                            
                            @if($item->title == 'WhatsApp' && $item->url)
                            <a href="{{ $item->url }}" target="_blank" title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @endif
                            
                            @if($item->title == 'Facebook' && $item->url)
                            <a href="{{ $item->url }}" target="_blank" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            @endif
                            
                            @if($item->title == 'YouTube' && $item->url)
                            <a href="{{ $item->url }}" target="_blank" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                            @endif
                        @endforeach
                    </div>
                    
                    <h5 class="mb-3">Jam Operasional</h5>
                    <p class="text-light">
                        <i class="bi bi-clock me-2"></i>
                        Senin - Jumat: 08:00 - 16:00<br>
                        <i class="bi bi-clock me-2"></i>
                        Sabtu: 08:00 - 12:00
                    </p>
                </div>
            </div>
            
            <hr class="bg-light">
            
            <div class="row">
                <div class="col-md-12 text-center">
                    @foreach($footerData as $item)
                        @if($item->title == 'Copyright' && $item->description)
                        <p class="mb-0">{{ $item->description }}</p>
                        @endif
                    @endforeach
                    
                    <p class="text-muted mt-2">
                        <small>Powered by PesantrenKita - Sistem Manajemen Pesantren</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-3 rounded-circle" 
            style="width: 50px; height: 50px; display: none;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize carousel
        var myCarousel = new bootstrap.Carousel(document.getElementById('mainCarousel'), {
            interval: 5000,
            wrap: true
        });

        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll to top button
        const scrollToTopBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Load more gallery (if implemented)
        const loadMoreBtn = document.getElementById('loadMoreGallery');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                // Implement load more functionality here
                this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Loading...';
                this.disabled = true;
                
                // Simulate loading
                setTimeout(() => {
                    alert('Load more functionality would load more images here');
                    this.innerHTML = '<i class="bi bi-plus-circle me-1"></i>Load More';
                    this.disabled = false;
                }, 1000);
            });
        }

        // Navbar active state
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 100) {
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
    </script>
</body>
</html>