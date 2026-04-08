<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pink-50 p-6">
        <div class="max-w-md w-full bg-white rounded-[3rem] shadow-2xl p-12 border border-pink-100 text-center">
            
            <div class="mb-8">
                <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2-2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h2 class="text-3xl font-black italic text-gray-900 mb-2 uppercase tracking-tighter">Reset Password</h2>
                <p class="text-xs text-gray-500 font-bold leading-relaxed">
                    Link reset akan langsung dikirim ke email terdaftar admin.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-black rounded-2xl">
                    Link berhasil dikirim! Silakan cek inbox Anda.
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                @csrf
                <button type="submit" id="submitBtn" class="w-full bg-gray-900 text-white font-black py-5 rounded-2xl hover:bg-pink-600 transition-all shadow-xl shadow-pink-200 uppercase tracking-widest text-xs">
                    Kirim Link Reset
                </button>

                <div id="countdownArea" class="hidden mt-6">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Kirim ulang tersedia dalam: <span id="timer" class="text-pink-600">60</span> detik
                    </p>
                </div>
            </form>

            <a href="{{ route('login') }}" class="inline-block mt-8 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-pink-600 transition-all italic">
                ← Kembali ke Login
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('resetForm');
        const btn = document.getElementById('submitBtn');
        const countdownArea = document.getElementById('countdownArea');
        const timerText = document.getElementById('timer');

        // Memeriksa apakah sedang dalam masa tunggu (setelah klik)
        if (localStorage.getItem('reset_cooldown')) {
            startTimer(localStorage.getItem('reset_cooldown'));
        }

        form.onsubmit = function() {
            const expiry = Date.now() + 60000;
            localStorage.setItem('reset_cooldown', expiry);
            startTimer(expiry);
        };

        function startTimer(expiry) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerText = "LINK TELAH DIKIRIM";
            countdownArea.classList.remove('hidden');

            const interval = setInterval(() => {
                const remaining = Math.round((expiry - Date.now()) / 1000);
                
                if (remaining <= 0) {
                    clearInterval(interval);
                    localStorage.removeItem('reset_cooldown');
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btn.innerText = "KIRIM ULANG LINK RESET";
                    countdownArea.classList.add('hidden');
                } else {
                    timerText.innerText = remaining;
                }
            }, 1000);
        }
    </script>
</x-guest-layout>