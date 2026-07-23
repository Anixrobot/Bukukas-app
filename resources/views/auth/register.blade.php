<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daftar - BukuKas App</title>
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
    
    <div class="w-full max-w-md bg-white h-full sm:h-auto sm:rounded-3xl sm:shadow-2xl flex flex-col relative overflow-y-auto sm:overflow-hidden p-8">
        
        <div class="text-center mt-6 mb-6">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl">📝</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Akun Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Lengkapi data untuk bergabung</p>
        </div>

        <!-- Alert Error Laravel -->
        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-3 rounded-xl text-sm mb-4 border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm" class="flex flex-col gap-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required autofocus class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-candyBlue focus:ring-2 focus:ring-blue-100 outline-none transition" placeholder="Budi Santoso" value="{{ old('name') }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-candyBlue focus:ring-2 focus:ring-blue-100 outline-none transition" placeholder="contoh@email.com" value="{{ old('email') }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-candyBlue focus:ring-2 focus:ring-blue-100 outline-none transition" placeholder="••••••••">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-candyBlue focus:ring-2 focus:ring-blue-100 outline-none transition" placeholder="••••••••">
            </div>

            <button type="submit" id="btnSubmitReg" class="active-scale w-full bg-candyBlue hover:bg-candyBlueDark text-white font-bold py-3.5 rounded-xl transition mt-2 flex justify-center items-center gap-2">
                <span id="btnTextReg">Daftar Sekarang</span>
                <svg id="btnSpinnerReg" class="animate-spin hidden h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6 pb-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-candyBlue font-semibold hover:underline">Masuk di sini</a>
        </p>
    </div>

    <!-- Script Animasi Loading -->
    <script>
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmitReg');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            document.getElementById('btnTextReg').innerText = 'Memproses...';
            document.getElementById('btnSpinnerReg').classList.remove('hidden');
        });
    </script>
</body>
</html>