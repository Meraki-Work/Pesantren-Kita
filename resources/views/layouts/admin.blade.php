@extends('index')

@section('content')

<div class="flex">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />
    <!-- Main Content -->
    <main class="flex-1 px-6 pt-4 pb-6 overflow-y-auto">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-md-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMobile">
                    <i class="bi bi-list"></i>
                </button>
                <div class="navbar-nav ms-auto">
                    <span class="nav-item nav-link">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ 'admin ' . (Auth::user()->username ?? 'Admin') }}
                    </span>
                </div>
            </div>
        </nav>

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

            @yield('page-content')
        </div>
    </main>
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