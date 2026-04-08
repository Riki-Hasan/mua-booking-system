<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location; // Import Model Location
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Menampilkan daftar wilayah
    public function index()
    {
        $locations = Location::all();
        return view('admin.locations.index', compact('locations'));
    }

    // Menyimpan wilayah baru
    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required',
            'additional_price' => 'required|numeric',
        ]);

        Location::create($request->all());

        return back()->with('success', 'Wilayah berhasil ditambahkan!');
    }

    // Menghapus wilayah
    public function destroy($id)
    {
        Location::findOrFail($id)->delete();
        return back()->with('success', 'Wilayah berhasil dihapus!');
    }
}