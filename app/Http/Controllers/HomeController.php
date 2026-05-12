<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Bundling;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil kategori dengan urutan: Angka terkecil di atas, NULL di paling bawah
        $categories = Category::orderByRaw('sort_order IS NULL, sort_order ASC')->get();

        // Ambil portofolio yang ikut urutan kategori tersebut
        $portfolios = Portfolio::with('category')
            ->join('categories', 'portfolios.category_id', '=', 'categories.id')
            ->orderByRaw('categories.sort_order IS NULL, categories.sort_order ASC')
            ->orderBy('portfolios.created_at', 'desc')
            ->select('portfolios.*')
            ->get();

        $bundlings = Bundling::where('is_active', true)->get();

        $kebayas = \App\Models\Kebaya::latest()->get();

        return view('welcome', compact('categories', 'portfolios', 'bundlings', 'kebayas'));
    }
}
