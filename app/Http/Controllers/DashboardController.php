<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'productsCount' => Product::count(),
            'categoriesCount' => Category::count(),
            'suppliersCount' => Supplier::count(),
            'sectionsCount' => Section::count(),
            'lowStockCount' => Product::where('stock', '<', 5)->count(),
            'recentProducts' => Product::with(['category', 'supplier', 'section'])
                ->latest()
                ->take(5)
                ->get(),
            'lowStockProducts' => Product::with(['category', 'section'])
                ->where('stock', '<', 5)
                ->orderBy('stock')
                ->take(5)
                ->get(),
        ]);
    }
}
