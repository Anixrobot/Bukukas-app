<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Kas Super</title>
    <!-- Manggil CSS Bootstrap biar web langsung cakep -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar / Menu Atas -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">BukuKas Apps</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Kumpulan menu di kanan navbar -->
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Nanti link ini bakal kita arahin ke route Laravel -->
                    <li class="nav-item">
                        <a class="nav-link" href="/kas-kelas">🏢 Kas Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pribadi">💰 Kas Pribadi</a>
                    </li>
                    
                    <!-- Tombol Logout -->
                    <li class="nav-item ms-lg-3">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger border-0 fw-bold d-flex align-items-center gap-2">
                                <!-- Icon Orang (SVG) -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tempat nongolnya konten dari file dashboard lain -->
    <div class="container">
        @yield('konten')
    </div>

    <!-- Manggil Javascript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>