<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Pesantren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 2px 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #495057;
            color: white;
        }
        .navbar-brand {
            font-weight: 600;
        }
        .content-wrapper {
            background: #f8f9fa;
            min-height: 100vh;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="p-3">
                    <h5 class="text-white mb-4">Admin Panel</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.ponpes.*') ? 'active' : '' }}" 
                               href="{{ route('admin.ponpes.index') }}">
                                <i class="bi bi-house-door me-2"></i>Pesantren
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.landing-content.*') ? 'active' : '' }}" 
                               href="{{ route('admin.landing-content.index') }}">
                                <i class="bi bi-window me-2"></i>Landing Page
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('landing.index') }}" target="_blank">
                                <i class="bi bi-eye me-2"></i>View Site
                            </a>
                        </li>
                        <hr class="bg-white">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link text-start w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 content-wrapper p-0">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                    <div class="container-fluid">
                        <button class="btn btn-outline-secondary d-md-none" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#sidebarMobile">
                            <i class="bi bi-list"></i>
                        </button>
                        
                        <a class="navbar-brand ms-2" href="#">
                            <i class="bi bi-house-heart text-primary me-2"></i>
                            PesantrenKita Admin
                        </a>
                        
                        <div class="navbar-nav ms-auto">
                            <span class="nav-item nav-link">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->username ?? 'Admin' }}
                            </span>
                        </div>
                    </div>
                </nav>
                
                <!-- Mobile Sidebar -->
                <div class="collapse d-md-none" id="sidebarMobile">
                    <div class="bg-dark p-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('admin.ponpes.index') }}">
                                    <i class="bi bi-house-door me-2"></i>Pesantren
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('admin.landing-content.index') }}">
                                    <i class="bi bi-window me-2"></i>Landing Page
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Page Content -->
                <div class="p-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
        
        // Show toast messages from session
        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif
        
        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif
    </script>
    @stack('scripts')
</body>
</html>