<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['category', 'supplier', 'section'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'));
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->integer('section_id'));
            })
            ->when($request->input('stock') === 'low', function ($query) {
                $query->where('stock', '<', 5);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'productsCount' => Product::count(),
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'categories' => Category::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            StockMovement::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'type' => 'initial',
                'quantity' => $product->stock,
                'stock_before' => 0,
                'stock_after' => $product->stock,
                'notes' => 'Saldo inicial al registrar el producto.',
            ]);
        });

        return redirect()->route('products.index')->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($product, $validated) {
            $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();
            $stockBefore = $lockedProduct->stock;
            $stockAfter = (int) $validated['stock'];
            $lockedProduct->update($validated);

            if ($stockBefore !== $stockAfter) {
                StockMovement::create([
                    'product_id' => $lockedProduct->id,
                    'product_name' => $lockedProduct->name,
                    'type' => 'adjustment',
                    'quantity' => abs($stockAfter - $stockBefore),
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'notes' => 'Ajuste de saldo realizado desde la edición del producto.',
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();

            if ($lockedProduct->stock > 0) {
                StockMovement::create([
                    'product_id' => $lockedProduct->id,
                    'product_name' => $lockedProduct->name,
                    'type' => 'exit',
                    'quantity' => $lockedProduct->stock,
                    'stock_before' => $lockedProduct->stock,
                    'stock_after' => 0,
                    'notes' => 'Salida registrada al eliminar el producto del inventario.',
                ]);
            }

            $lockedProduct->delete();
        });

        return redirect()->route('products.index')->with('success', 'Producto eliminado. Su historial de movimientos se conservó.');
    }

    private function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'supplier_id' => ['required', 'integer', Rule::exists('suppliers', 'id')],
            'section_id' => ['required', 'integer', Rule::exists('sections', 'id')],
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
}
