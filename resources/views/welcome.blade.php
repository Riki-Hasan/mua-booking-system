@extends('layouts.app')

@section('title', 'Halaman Uji Coba')

@section('content')
    <div class="bg-white p-10 rounded-3xl shadow-xl border border-pink-100 text-center max-w-2xl mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
            Tailwind Berhasil Aktif! 🚀
        </h1>
        <p class="text-gray-600 mb-8">
            Jika kamu melihat kotak ini dengan bayangan halus dan tombol berwarna pink di bawah, 
            berarti konfigurasi manual Tailwind dan Vite kamu sudah benar.
        </p>
        
        <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-8 rounded-full transition-all shadow-lg shadow-pink-200">
            Cek Portofolio
        </button>
    </div>
@endsection