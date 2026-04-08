<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pink-50 p-6">
        <div class="max-w-md w-full bg-white rounded-[3rem] shadow-2xl p-12 border border-pink-100">
            <h2 class="text-3xl font-black italic text-gray-900 mb-8 uppercase tracking-tighter">Password Baru</h2>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Password Baru</label>
                    <input type="password" name="password" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 transition-all" required>
                </div>

                <button type="submit" class="w-full bg-emerald-500 text-white font-black py-5 rounded-2xl hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-200 uppercase tracking-widest text-xs">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>