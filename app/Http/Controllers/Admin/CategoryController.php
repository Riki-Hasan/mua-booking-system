<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Portfolio; // Import Model Portfolio
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class CategoryController extends Controller
{
    // 1. Menampilkan daftar paket
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Simpan paket baru (Hanya Data Teks)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'duration_minutes' => 'required|numeric',
        ]);

        Category::create([
            'name' => $request->name,
            'base_price' => $request->base_price,
            'duration_minutes' => $request->duration_minutes,
        ]);

        return back()->with('success_edit', 'Paket baru berhasil ditambahkan!');
    }

    // 3. Update data paket (Teks saja)
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'duration_minutes' => 'required|numeric',
        ]);

        $category->update($request->only(['name', 'base_price', 'duration_minutes']));

        return back()->with('success_edit', 'Data paket ' . $category->name . ' berhasil diperbarui!');
    }

    // 4. Tambah Foto ke Gallery (Tabel Portfolios)
    public function updateImage(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Simpan file ke folder portfolio
            $path = $request->file('image')->store('portfolio', 'public');
            
            // Masukkan data ke tabel portfolios
            Portfolio::create([
                'category_id' => $request->category_id,
                'image_path' => $path
            ]);
        }

        return back()->with('success_edit', 'Foto berhasil ditambahkan ke gallery!');
    }

    // 5. Hapus Foto Portfolio secara spesifik
    // Method untuk menghapus foto portfolio secara spesifik
    public function destroyPortfolio($id)
    {
        $photo = \App\Models\Portfolio::findOrFail($id);
        
        // Hapus file fisik dari storage agar tidak menumpuk
        if ($photo->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->image_path);
        }
        
        $photo->delete();
        
        return back()->with('success_delete', 'Foto portfolio berhasil dihapus!');
    }

    // 6. Hapus Paket (Dengan Proteksi Foreign Key)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        try {
            // Sebelum hapus kategori, hapus semua file foto terkait di storage
            foreach ($category->portfolios as $portfolio) {
                Storage::disk('public')->delete($portfolio->image_path);
            }

            // Hapus kategori (Otomatis data di tabel portfolios terhapus jika pakai cascade)
            $category->delete();

            return back()->with('success_delete', 'Paket berhasil dihapus secara permanen.');

        } catch (Exception $e) {
            // Jika gagal karena Foreign Key (ada di tabel bookings)
            return back()->with('error_delete', 'Gagal menghapus! Paket ini tidak bisa dihapus karena masih memiliki riwayat data pesanan pelanggan.');
        }
    }
}