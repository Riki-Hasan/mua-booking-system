<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'base_price' => 'required|numeric',
            'duration_minutes' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        // Simpan foto ke folder storage/app/public/portfolio
        $imagePath = $request->file('image')->store('portfolio', 'public');

        \App\Models\Category::create([
            'name' => $request->name,
            'base_price' => $request->base_price,
            'duration_minutes' => $request->duration_minutes,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Paket berhasil ditambahkan!');
    }
}
