<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - BukuKas App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        candyBlue: '#00B4D8',
                        candyBlueDark: '#0096C7',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #e5e7eb; }
        .active-scale:active { transform: scale(0.96); transition: transform 0.1s; }
    </style>
</head>
<body class="flex justify-center items-center h-screen overflow-hidden">
    
    <!-- MOBILE APP CONTAINER -->
    <div class="w-full max-w-md bg-white h-full sm:h-auto sm:rounded-3xl sm:shadow-2xl flex flex-col relative overflow-hidden p-8">
        
        <!-- Header Text -->
        <div class="text-center mt-10 mb-8">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl">📘</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">BukuKas App</h1>
            <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- Alert Pesan Error Bawaan Laravel -->
        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-3 rounded-xl text-sm mb-4 border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm" class="flex flex-col gap-4">
            @csrf
            
            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required autofocus class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-candyBlue focus:ring-2 focus:ring-blue-100 outline-none transition" placeholder="contoh@email.com" value="{{ old('email') }}">
            </div>
            
            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-candyBlue focus:ring-2 focus:ring-blue-100 outline-none transition" placeholder="••••••••">
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex justify-between items-center text-sm mt-1">
                <label class="flex items-center text-gray-600 gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded text-candyBlue focus:ring-candyBlue">
                    Ingat Saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-candyBlue font-semibold hover:underline">Lupa Password?</a>
                @endif
            </div>

            <!-- Tombol Login dengan Animasi Loading -->
            <button type="submit" id="btnSubmit" class="active-scale w-full bg-candyBlue hover:bg-candyBlueDark text-white font-bold py-3.5 rounded-xl transition mt-4 flex justify-center items-center gap-2">
                <span id="btnText">Masuk Sekarang</span>
                
                <!-- Spinner Bulat (Awalnya disembunyiin) -->
                <svg id="btnSpinner" class="animate-spin hidden h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <!-- Garis Pembatas -->
        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="mx-4 text-sm text-gray-400">atau login dengan</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Tombol Login Google -->
        <a href="{{ route('google.login') }}" class="active-scale w-full bg-white border border-gray-300 text-gray-700 font-semibold py-3.5 rounded-xl hover:bg-gray-50 transition flex justify-center items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Masuk pakai Google
        </a>

        <!-- Link Register -->
        <p class="text-center text-sm text-gray-600 mt-auto pt-8">
            Belum punya akun? <a href="{{ route('register') }}" class="text-candyBlue font-semibold hover:underline">Daftar di sini</a>
        </p>
    </div>

    <!-- Script buat ngaktifin efek loading -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Matiin tombol biar nggak bisa diklik 2 kali
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Ubah teks dan munculin logo loading muter-muter
            btnText.innerText = 'Memproses...';
            btnSpinner.classList.remove('hidden');
        });
    </script>
</body>
</html>