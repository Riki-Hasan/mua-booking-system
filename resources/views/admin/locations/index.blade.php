<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-pink-600 leading-tight">
            {{ __('Manajemen Wilayah & Ongkir') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-pink-50">
                <h3 class="font-black text-gray-800 mb-6 uppercase tracking-widest text-sm">Tambah Wilayah</h3>
                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Nama Wilayah</label>
                            <input type="text" name="region_name" placeholder="Contoh: Adiwerna" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Biaya Tambahan (Rp)</label>
                            <input type="number" name="additional_price" placeholder="Contoh: 15000" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 text-sm" required>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all shadow-lg shadow-gray-200">
                            SIMPAN WILAYAH
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                        <tr>
                            <th class="px-8 py-6">Nama Wilayah</th>
                            <th class="px-8 py-6">Ongkir Tambahan</th>
                            <th class="px-8 py-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($locations as $location)
                        <tr class="hover:bg-pink-50/30 transition-colors">
                            <td class="px-8 py-6 font-bold text-gray-800">{{ $location->region_name }}</td>
                            <td class="px-8 py-6 font-medium text-pink-600 italic">Rp{{ number_format($location->additional_price, 0, ',', '.') }}</td>
                            <td class="px-8 py-6 text-center">
                                <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Hapus wilayah ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-500 hover:text-rose-700 transition-colors p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>