<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Bundling;
use App\Models\Kebaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $portfolios = Portfolio::with('category')->latest()->get();
        $bundlings = Bundling::latest()->get();
        $kebayas = Kebaya::latest()->get();

        return view('admin.categories.index', compact('categories', 'portfolios', 'bundlings', 'kebayas'));
    }

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

        return back()->with([
            'success_edit' => 'Paket baru berhasil ditambahkan!',
            'current_tab' => $request->current_tab
        ]);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('portfolio', 'public');
            Portfolio::create([
                'category_id' => $request->category_id,
                'image_path' => $path
            ]);
        }

        return back()->with([
            'success_edit' => 'Foto berhasil ditambahkan ke gallery!',
            'current_tab' => $request->current_tab
        ]);
    }

    public function updateOrder(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $index => $id) {
            Category::where('id', $id)->update([
                'sort_order' => $index + 1
            ]);
        }
        return response()->json(['status' => 'success', 'message' => 'Urutan berhasil diperbarui!']);
    }

    public function storeKebaya(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('kebayas', 'public');

        Kebaya::create([
            'name' => $request->name,
            'image_path' => $path
        ]);

        return back()->with([
            'success_edit' => 'Koleksi Kebaya berhasil ditambahkan!',
            'current_tab' => 'kebaya'
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        try {
            $request->validate([
                'name' => 'required|string|max:255', 
                'base_price' => 'required|numeric', 
                'duration_minutes' => 'required|numeric'
            ]);
            
            $category->update($request->only(['name', 'base_price', 'duration_minutes']));

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Data paket berhasil diperbarui!']);
            }
            return back()->with(['success_edit' => 'Data paket berhasil diperbarui!', 'current_tab' => $request->current_tab]);
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 422);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroyPortfolio(Request $request, $id)
    {
        $photo = Portfolio::findOrFail($id);
        if ($photo->image_path) { 
            Storage::disk('public')->delete($photo->image_path); 
        }
        $photo->delete();

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Foto berhasil dihapus!']);
        }
        return back()->with(['success_delete' => 'Foto portfolio berhasil dihapus!', 'current_tab' => $request->current_tab]);
    }

    public function destroyKebaya(Request $request, $id)
    {
        $kebaya = Kebaya::findOrFail($id);
        if ($kebaya->image_path) { 
            Storage::disk('public')->delete($kebaya->image_path); 
        }
        $kebaya->delete();

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Koleksi Kebaya berhasil dihapus!']);
        }
        return back()->with(['success_delete' => 'Kebaya berhasil dihapus!', 'current_tab' => $request->current_tab]);
    }

    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        try {
            foreach ($category->portfolios as $portfolio) { 
                Storage::disk('public')->delete($portfolio->image_path); 
            }
            $category->delete();
            
            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Paket berhasil dihapus secara permanen.']);
            }
            return back()->with(['success_delete' => 'Paket berhasil dihapus.', 'current_tab' => $request->current_tab]);
            
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal menghapus! Paket masih digunakan.'], 422);
            }
            return back()->with(['error_delete' => 'Gagal menghapus!']);
        }
    }
}