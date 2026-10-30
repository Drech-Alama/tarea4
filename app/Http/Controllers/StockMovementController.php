<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $movements = StockMovement::with('product')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('product_name', 'like', $search)
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->string('type')->toString());
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('stock-movements.index', compact('movements'));
    }

    public function create(Request $request): View
    {
        return view('stock-movements.create', [
            'products' => Product::orderBy('name')->get(),
            'selectedProductId' => $request->integer('product_id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'in:entry,exit'],
            'quantity' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $movement = DB::transaction(function () use ($validated) {
            $product = Product::query()
                ->whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $before = $product->stock;
            $quantity = (int) $validated['quantity'];

            if ($validated['type'] === 'exit' && $quantity > $before) {
                throw ValidationException::withMessages([
                    'quantity' => 'La salida no puede ser mayor que el stock disponible ('.$before.' unidades).',
                ]);
            }

            $after = $validated['type'] === 'entry'
                ? $before + $quantity
                : $before - $quantity;

            if ($after > 4294967295) {
                throw ValidationException::withMessages([
                    'quantity' => 'El stock resultante supera el máximo permitido.',
                ]);
            }

            $product->update(['stock' => $after]);

            return StockMovement::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'stock_before' => $before,
                'stock_after' => $after,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('stock-movements.index')
            ->with('success', 'Movimiento registrado. El stock de '.$movement->product->name.' ahora es '.$movement->stock_after.' unidades.');
    }
}
