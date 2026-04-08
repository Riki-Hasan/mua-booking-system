<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | MUA Professional</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black tracking-tighter text-gray-900">MUA<span class="text-pink-600">.</span></h1>
            <p class="text-gray-500 text-sm font-medium uppercase tracking-widest mt-2">Admin Central</p>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl shadow-pink-200/50 border border-pink-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Selamat Datang Kembali</h2>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-xl text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Email Admin</label>
                    <input type="email" name="email" :value="old('email')" required autofocus 
                        class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none border text-gray-700"
                        placeholder="admin@gmail.com">
                    @error('email') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-pink-500 focus:bg-white transition-all outline-none border text-gray-700"
                            placeholder="••••••••">
                        
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-600 transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between mb-8">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded-lg border-gray-200 text-pink-600 focus:ring-pink-500">
                        <span class="ml-2 text-sm text-gray-500">Ingat saya</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-pink-600 font-bold hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all shadow-lg hover:shadow-pink-200 active:scale-[0.98] uppercase tracking-widest">
                    LOGIN
                </button>
            </form>
        </div>

        <p class="text-center mt-8 text-sm text-gray-400">
            &copy; 2026 MUA Booking System. <br> 
            <a href="/" class="text-pink-600 font-bold hover:underline">Kembali ke Beranda</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L4.5 4.5m15 15l-5.88-5.88M21.542 12a10.05 10.05 0 00-1.563-3.029m-5.858-3.071A10.003 10.003 0 0012 5c-4.478 0-8.268 2.943-9.543 7a10.025 10.025 0 004.132 5.411m0 0L21.5 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>
</html>