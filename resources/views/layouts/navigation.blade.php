<nav x-data="{ openModal: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="shrink-0">
                    <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                </a>
                
                <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 text-pink-600 font-black hover:text-pink-700 transition-all">
                    <div class="bg-pink-50 p-2 rounded-xl">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </div>
                    <span class="hidden sm:block text-[10px] uppercase tracking-widest">Beranda Client</span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden xs:block">
                    <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1">Admin MUA</p>
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                </div>

                <button @click="openModal = true" class="w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-pink-600 transition-all shadow-lg active:scale-90">
                    <i class="fa-solid fa-gear text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="openModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="openModal = false"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;">
        
        <div class="absolute inset-0" @click="openModal = false"></div>
        
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl border border-pink-50 relative transform transition-all overflow-hidden">
            
            <button @click="openModal = false" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-400 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-user-gear text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 uppercase italic tracking-tighter leading-none">Tentang Akun</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 mb-4">{{ Auth::user()->email }}</p>
                
                <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-6 py-2 rounded-xl font-black uppercase text-[9px] tracking-widest hover:bg-blue-50 hover:text-blue-600 transition-all">
                    <i class="fa-solid fa-user-pen"></i> Edit Profil
                </a>
            </div>

            <hr class="border-gray-50 mb-6">

            <form action="{{ route('admin.profile.settings.update') }}" method="POST" class="space-y-6">
                @csrf @method('PATCH')
                <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-3 ml-1 tracking-[0.2em]">Notifikasi Pengingat Jadwal</label>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-gray-500">H -</span>
                        <input type="number" name="reminder_days" value="{{ Auth::user()->reminder_days ?? 2 }}" class="flex-1 bg-white border-gray-200 rounded-xl p-3 text-sm font-black focus:ring-2 focus:ring-pink-500 outline-none text-center" min="1" max="30">
                        <span class="text-sm font-bold text-gray-500 uppercase">Hari</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px] tracking-widest hover:bg-emerald-600 transition-all cursor-pointer">
                    Simpan Pengaturan
                </button>
            </form> 
            <a href="{{ route('admin.test.reminder') }}" class="w-full bg-blue-500 text-white text-center font-black py-4 rounded-2xl hover:bg-blue-600 transition-all uppercase text-[10px] tracking-widest mb-2">
                🚀 Test Kirim Email Sekarang
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full bg-rose-50 text-rose-600 font-black py-4 rounded-2xl hover:bg-rose-600 hover:text-white transition-all uppercase text-[10px] tracking-widest flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-power-off"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>