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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Nanti link ini bakal kita arahin ke route Laravel -->
                    <li class="nav-item">
                        <a class="nav-link" href="/kas-kelas">🏢 Kas Kelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pribadi">💰 Kas Pribadi</a>
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