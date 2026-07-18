@php
    // Mengambil dan menghapus session pesan sukses agar anti-refresh
    $msgSukses = session()->pull('success_settings');
@endphp

<!-- Dibuat tanpa ketergantungan Alpine untuk penanganan modal utama demi kestabilan tablet -->
<nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="shrink-0">
                    <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                </a>
                
                <!-- Perbaikan 4: Menampilkan teks singkat 'Cek Web Client' di semua ukuran layar (Mobile & Tab) -->
                <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-1.5 text-pink-600 font-black hover:text-pink-700 transition-all touch-manipulation">
                    <div class="bg-pink-50 p-2 rounded-xl">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </div>
                    <span class="text-[9px] uppercase tracking-wider font-black">Cek Web Client</span>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden xs:block">
                    <p class="text-[10px] font-black text-gray-400 uppercase leading-none mb-1">Admin MUA</p>
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                </div>

                <!-- Perbaikan 1: Memicu fungsi openAdminSettingsModal() bawaan browser native (Anti-Mogok di Tablet) -->
                <button onclick="openAdminSettingsModal()" class="w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center hover:bg-pink-600 transition-all shadow-lg active:scale-90 cursor-pointer touch-manipulation">
                    <i class="fa-solid fa-gear text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 🚨 REVISI TOTAL: MODAL OVERLAY KHUSUS PENGATURAN AKUN      -->
    <!-- ======================================================== -->
    <!-- Mengunci posisi penuh layar menggunakan RGBA Tradisional agar ramah tablet lama -->
    <div id="admin-settings-modal" 
         class="hidden no-print animate-pop"
         style="display: none; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100% !important; height: 100% !important; bg-color: transparent; background-color: rgba(15, 23, 42, 0.7) !important; z-index: 2147483647 !important; align-items: center; justify-content: center; padding: 16px;">
        
        <!-- Backdrop klik penutup modal di belakang layar -->
        <div class="absolute inset-0 cursor-pointer" onclick="closeAdminSettingsModal()" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 1;"></div>
        
        <!-- 🚨 KOTAK PUTIH UTAMA MODAL: Mengunci batas tinggi max-h agar tidak tenggelam dan mengaktifkan scroll -->
        <div class="relative bg-white rounded-[2rem] max-w-md w-full p-6 shadow-2xl border border-pink-100 text-left"
             style="z-index: 2 !important; box-border: border-box !important; max-height: 85vh !important; overflow-y: auto !important; display: block !important;">
            
            <button onclick="closeAdminSettingsModal()" class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>

            <!-- Header Modal -->
            <div class="text-center mt-2 mb-4">
                <div class="w-12 h-12 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid fa-user-gear text-xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Tentang Akun</h3>
                <p class="text-xs text-slate-700 font-extrabold uppercase tracking-wider mt-2 mb-2 bg-slate-100 inline-block px-3 py-1 rounded-md shadow-sm">{{ Auth::user()->email }}</p>
                <br>
                <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-1.5 bg-pink-50 text-pink-600 px-5 py-2 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-pink-600 hover:text-white transition-all shadow-sm">
                    <i class="fa-solid fa-user-pen"></i> Edit Profil
                </a>
            </div>

            <hr class="border-slate-100 mb-4">

            <!-- FORM GABUNGAN -->
            <form action="{{ route('admin.profile.full_update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf 
                @method('PATCH')

                {{-- Upload Foto --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-900 uppercase tracking-wider ml-1" style="color: #111827 !important; font-weight: 900 !important;">Foto Branding MUA (Halaman Depan)</label>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-24 h-32 bg-white rounded-xl overflow-hidden border border-pink-200 flex-shrink-0 flex items-center justify-center shadow-md">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user-tie text-pink-200 text-3xl"></i>
                            @endif
                        </div>
                        <div class="flex-1 w-full text-center sm:text-left space-y-2">
                            <input type="file" name="profile_photo" class="w-full text-[10px] text-slate-700 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-slate-900 file:text-white hover:file:bg-pink-600 cursor-pointer shadow-sm">
                            <p class="text-[10px] text-slate-950 font-black uppercase tracking-wider bg-yellow-100 inline-block px-2 py-0.5 rounded">* Format Wajib: JPG / PNG</p>
                        </div>
                    </div>
                </div>

                {{-- Input Reminder --}}
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="block text-xs font-black text-slate-900 uppercase mb-2 ml-1 tracking-wider" style="color: #111827 !important; font-weight: 900 !important;">Notifikasi Pengingat Jadwal</label>
                    
                    <!-- REVISI: Menambahkan 'justify-center' agar seluruh isi inputan ini bergeser pas di tengah kontainer -->
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-sm font-bold text-slate-800">H -</span>
                        
                        <input type="number" name="reminder_days" value="{{ Auth::user()->reminder_days ?? 2 }}" 
                            class="w-24 bg-white border border-slate-300 rounded-xl p-2.5 text-sm font-black focus:ring-2 focus:ring-pink-500 focus:border-pink-500 outline-none text-center text-slate-900 shadow-sm" 
                            min="1" max="30" 
                            style="background-color: #ffffff !important; color: #000000 !important; font-weight: 900 !important; border: 2px solid #cbd5e1 !important;">
                        
                        <span class="text-sm font-bold text-slate-800 uppercase ml-1">Hari</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-500 text-white font-black py-4 rounded-xl shadow-lg uppercase text-[10px] tracking-widest hover:bg-emerald-600 transition-all cursor-pointer shadow-emerald-100 active:scale-[0.98]">
                    Simpan Pengaturan
                </button>
            </form> 

            <!-- Form Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t border-slate-100 pt-3">
                @csrf
                <button type="submit" class="w-full bg-rose-50 text-rose-600 font-black py-3.5 rounded-xl hover:bg-rose-600 hover:text-white transition-all uppercase text-[10px] tracking-widest flex items-center justify-center gap-2 cursor-pointer shadow-sm active:scale-[0.98]">
                    <i class="fa-solid fa-power-off text-xs"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL STATUS BERHASIL -->
    @if($msgSukses)
    <div id="modal-success-container" 
         class="fixed inset-0 z-[2147483647] flex items-center justify-center p-4 no-print"
         style="position: fixed !important; top:0; left:0; width:100%; height:100%; background-color: rgba(15, 23, 42, 0.6) !important; display: flex !important; align-items: center; justify-content: center;">
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center border-4 border-emerald-50" style="display: block !important;">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check-double text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Tersimpan!</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                {{ $msgSukses }}
            </p>
            <button onclick="closeSuccessModal()" 
                    class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px] tracking-widest hover:bg-pink-600 transition-all shadow-lg active:scale-95 touch-manipulation">
                Oke, Siap
            </button>
        </div>
    </div>
    <script>
        function closeSuccessModal() {
            const modal = document.getElementById('modal-success-container');
            if(modal) {
                modal.style.display = 'none';
                modal.remove();
            }
        }
    </script>
    @endif

    <!-- Native JavaScript Controller untuk Pengaturan Akun -->
    <script>
        function openAdminSettingsModal() {
            const modal = document.getElementById('admin-settings-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.setProperty('display', 'flex', 'important');
            }
        }

        function closeAdminSettingsModal() {
            const modal = document.getElementById('admin-settings-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.setProperty('display', 'none', 'important');
            }
        }
    </script>
</nav>