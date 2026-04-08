<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter">
                {{ __('Manajemen Portfolio & Paket') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-pink-100 hover:text-pink-600 transition-all active:scale-95">
                <i class="fa-solid fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12" x-data="{ tab: 'paket' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-center gap-2 mb-8">
                <button @click="tab = 'paket'" 
                        :class="tab === 'paket' ? 'bg-pink-600 text-white shadow-lg border-pink-600' : 'bg-white text-gray-400 border-gray-100'"
                        class="flex-1 max-w-[180px] px-4 py-4 rounded-2xl font-black uppercase text-[10px] transition-all border-2">
                    <i class="fa-solid fa-box-archive mr-1"></i> Paket
                </button>
                <button @click="tab = 'konten'" 
                        :class="tab === 'konten' ? 'bg-pink-600 text-white shadow-lg border-pink-600' : 'bg-white text-gray-400 border-gray-100'"
                        class="flex-1 max-w-[180px] px-4 py-4 rounded-2xl font-black uppercase text-[10px] transition-all border-2">
                    <i class="fa-solid fa-images mr-1"></i> Konten
                </button>
            </div>

            <div x-show="tab === 'paket'" x-transition>
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
                    <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Tambah Paket Baru</h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div class="md:col-span-1">
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Paket</label>
                            <input type="text" name="name" placeholder="Misal: Wedding" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-pink-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Harga (IDR)</label>
                            <input type="number" name="base_price" placeholder="500000" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Durasi (Menit)</label>
                            <input type="number" name="duration_minutes" placeholder="90" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
                        </div>
                        <button type="submit" class="bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all shadow-lg text-[10px] uppercase tracking-widest">
                            Simpan
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 text-[9px] uppercase text-gray-400 font-black tracking-widest">
                                <tr>
                                    <th class="px-6 py-6">Detail Paket</th>
                                    <th class="px-6 py-6 text-center">Harga</th>
                                    <th class="px-6 py-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($categories as $category)
                                <tr class="hover:bg-pink-50/20 transition-colors font-bold text-sm">
                                    <td class="px-6 py-6">
                                        <div class="text-gray-900 uppercase italic leading-none">{{ $category->name }}</div>
                                        <div class="text-[9px] text-gray-400 uppercase mt-1.5">{{ $category->duration_minutes }} Menit Rias</div>
                                    </td>
                                    <td class="px-6 py-6 text-pink-600 text-center font-black">Rp{{ number_format($category->base_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-6 text-center">
                                        <div class="flex justify-center gap-4">
                                            <button onclick="openEditModal({{ json_encode($category) }})" class="text-blue-400 hover:scale-125 transition-all"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <button onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}')" class="text-rose-400 hover:scale-125 transition-all"><i class="fa-solid fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'konten'" x-transition>
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
                    <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Upload Portfolio</h3>
                    <form action="{{ route('admin.categories.update_image') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        @csrf
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">Pilih Paket</label>
                            <select name="category_id" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none focus:ring-2 focus:ring-pink-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">File Gambar</label>
                            <input type="file" name="image" class="w-full text-[10px] font-bold text-gray-400 file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2" required>
                        </div>
                        <button type="submit" class="bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-emerald-500 transition-all text-[10px] uppercase">Upload</button>
                    </form>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach(\App\Models\Portfolio::with('category')->latest()->get() as $item)
                    <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group relative">
                        <div class="aspect-square overflow-hidden relative">
                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            
                            <div class="absolute top-2 right-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.portfolios.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari galeri?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-white/90 rounded-full text-rose-500 shadow-lg flex items-center justify-center">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="p-4 text-center md:text-left">
                            <h4 class="font-black text-gray-900 uppercase italic text-[10px] md:text-xs truncate leading-none">{{ $item->category->name }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="modalDelete" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl text-center border-4 border-rose-50 animate-pop">
            <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-trash-can text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Hapus Paket?</h3>
            <p class="text-sm text-gray-500 mb-8">Yakin ingin menghapus paket <strong id="deletePackageName"></strong>?</p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalDelete')" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl uppercase text-[10px]">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-rose-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px]">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] max-w-md w-full p-8 shadow-2xl border-4 border-blue-50 animate-pop">
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-6 text-center">Edit Paket</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-2 ml-1 block">Nama Paket</label>
                    <input type="text" name="name" id="edit_name" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-400 transition-all" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase mb-2 ml-1 block">Harga</label>
                        <input type="number" name="base_price" id="edit_price" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase mb-2 ml-1 block">Durasi (Menit)</label>
                        <input type="number" name="duration_minutes" id="edit_duration" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeModal('modalEdit')" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl uppercase text-[10px]">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalStatus" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center animate-pop">
            <div id="statusIcon" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"></div>
            <h3 id="statusTitle" class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2"></h3>
            <p id="statusMsg" class="text-sm text-gray-500 mb-8"></p>
            <button onclick="closeModal('modalStatus')" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px]">Oke</button>
        </div>
    </div>

    <style>
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>

    <script>
        function confirmDelete(id, name) {
            document.getElementById('deletePackageName').innerText = name;
            document.getElementById('deleteForm').action = `/admin/categories/${id}`;
            document.getElementById('modalDelete').classList.replace('hidden', 'flex');
        }

        function openEditModal(category) {
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_price').value = category.base_price;
            document.getElementById('edit_duration').value = category.duration_minutes;
            document.getElementById('editForm').action = `/admin/categories/${category.id}`;
            document.getElementById('modalEdit').classList.replace('hidden', 'flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.replace('flex', 'hidden');
        }

        @if(session('success_edit') || session('success_delete') || session('error_delete'))
            window.onload = () => {
                const icon = document.getElementById('statusIcon');
                const title = document.getElementById('statusTitle');
                const msg = document.getElementById('statusMsg');

                @if(session('success_edit'))
                    icon.className = "w-20 h-20 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6";
                    icon.innerHTML = '<i class="fa-solid fa-check-double text-3xl"></i>';
                    title.innerText = "Updated!";
                    msg.innerText = "{{ session('success_edit') }}";
                @elseif(session('success_delete'))
                    icon.className = "w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6";
                    icon.innerHTML = '<i class="fa-solid fa-check text-3xl"></i>';
                    title.innerText = "Berhasil!";
                    msg.innerText = "{{ session('success_delete') }}";
                @elseif(session('error_delete'))
                    icon.className = "w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce";
                    icon.innerHTML = '<i class="fa-solid fa-circle-exclamation text-3xl"></i>';
                    title.innerText = "Oops! Gagal";
                    msg.innerText = "{{ session('error_delete') }}";
                @endif
                document.getElementById('modalStatus').classList.replace('hidden', 'flex');
            }
        @endif
    </script>
</x-app-layout>