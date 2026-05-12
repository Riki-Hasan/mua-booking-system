@php
    // Metode 'pull' akan mengambil pesan SEKALIGUS menghapusnya dari session saat itu juga.
    // Jadi, jika halaman di-refresh atau diakses via 'Back', datanya sudah kosong (null).
    $msgSukses = session()->pull('success_settings');
@endphp

<nav x-data="{ 
    openModal: false, 
    showSuccess: false
}" 
x-init="
    @if($msgSukses)
        // Gunakan timeout agar Alpine.js benar-benar siap merender modalnya
        setTimeout(() => { showSuccess = true }, 100);
    @endif
"
class="bg-white border-b border-gray-100 sticky top-0 z-50">
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

    <!-- MODAL PENGATURAN AKUN -->
    <div x-show="openModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="openModal = false"
         class="fixed inset-0 z-[100] flex justify-center items-start overflow-y-auto p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-cloak>
        
        <div class="absolute inset-0" @click="openModal = false"></div>
        
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl border border-pink-50 relative transform transition-all my-auto">
            
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

            <!-- FORM GABUNGAN (FOTO & PENGATURAN) -->
            <form action="{{ route('admin.profile.full_update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf 
                @method('PATCH')

                {{-- Bagian Upload Foto --}}
                <div class="space-y-4">
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Foto Branding MUA (Halaman Depan)</label>
                    
                    <div class="bg-gray-50 p-4 rounded-3xl border border-gray-100 text-center">
                        <div class="w-24 h-32 bg-white rounded-2xl mx-auto mb-4 overflow-hidden border border-pink-100 flex items-center justify-center">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user-tie text-pink-200 text-3xl"></i>
                            @endif
                        </div>
                        <input type="file" name="profile_photo" class="w-full text-[9px] text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100 cursor-pointer">
                    </div>
                </div>

                {{-- Bagian Notifikasi --}}
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

            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-gray-50 pt-4">
                @csrf
                <button type="submit" class="w-full bg-rose-50 text-rose-600 font-black py-4 rounded-2xl hover:bg-rose-600 hover:text-white transition-all uppercase text-[10px] tracking-widest flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-power-off"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL STATUS BERHASIL (EMERALD STYLE) -->
    <div x-show="showSuccess" 
         x-cloak
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center border-4 border-emerald-50">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check-double text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Tersimpan!</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                {{ $msgSukses }}
            </p>
            <button @click="showSuccess = false" 
                    class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-pink-600 transition-all shadow-lg active:scale-95">
                Oke, Siap
            </button>
        </div>
    </div>
</nav>