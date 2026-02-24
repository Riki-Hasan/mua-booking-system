@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Manajemen Paket Jasa</h1>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-10">
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Nama Paket</label>
                    <input type="text" name="name" class="w-full border-gray-200 rounded-xl p-3" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Harga (Rp)</label>
                    <input type="number" name="base_price" class="w-full border-gray-200 rounded-xl p-3" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Durasi (Menit)</label>
                    <input type="number" name="duration_minutes" class="w-full border-gray-200 rounded-xl p-3" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Foto Portofolio</label>
                    <input type="file" name="image" class="text-sm" required>
                </div>
            </div>
            <button type="submit" class="mt-6 bg-pink-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-pink-700 transition">
                Simpan Paket
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-48 object-cover">
            <div class="p-5">
                <h3 class="font-bold text-lg">{{ $category->name }}</h3>
                <p class="text-pink-600 font-bold">Rp{{ number_format($category->base_price, 0, ',', '.') }}</p>
                <p class="text-gray-400 text-sm mt-1">⏳ {{ $category->duration_minutes }} menit</p>
                
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="mt-4">
                    @csrf @method('DELETE')
                    <button class="text-rose-600 text-sm font-bold uppercase tracking-widest hover:text-rose-800">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection