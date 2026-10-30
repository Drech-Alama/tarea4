@extends('layouts.app')
@section('title', 'Productos')
@section('page-title', 'Productos')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">CONTROL DE INVENTARIO</div><p>Consulta existencias, precios y detalles de tus productos.</p></div>
        <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Nuevo producto</a>
    </div>
    <div class="inventory-summary"><div class="summary-icon"><i class="bi bi-box-seam"></i></div><div><span class="text-muted">Total de productos registrados</span><strong>{{ number_format($productsCount) }}</strong></div></div>
    <section class="content-card">
        <div class="toolbar">
            <div><h3 class="mb-1">Inventario de productos</h3><div class="text-muted small">{{ $products->total() }} resultados</div></div>
            <form action="{{ route('products.index') }}" method="GET" class="filter-form">
                <label class="visually-hidden" for="search">Buscar producto</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar producto..."></div>
                <label class="visually-hidden" for="category_id">Filtrar por categoría</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>@endforeach
                </select>
                <label class="visually-hidden" for="section_id">Filtrar por sección</label>
                <select id="section_id" name="section_id" class="form-select">
                    <option value="">Todas las secciones</option>
                    @foreach ($sections as $section)<option value="{{ $section->id }}" @selected((string) request('section_id') === (string) $section->id)>{{ $section->name }}</option>@endforeach
                </select>
                <label class="visually-hidden" for="stock">Filtrar por stock</label>
                <select id="stock" name="stock" class="form-select">
                    <option value="">Cualquier stock</option>
                    <option value="low" @selected(request('stock') === 'low')>Stock bajo (&lt; 5)</option>
                </select>
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                @if (request()->hasAny(['search', 'category_id', 'section_id', 'stock']))<a href="{{ route('products.index') }}" class="btn btn-light" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>@endif
            </form>
        </div>
        @if ($products->isNotEmpty())
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th>Producto</th><th>Categoría</th><th>Proveedor</th><th>Sección</th><th>Precio</th><th>Stock disponible</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td><div class="fw-semibold">{{ $product->name }}</div><small class="text-muted">{{ \Illuminate\Support\Str::limit($product->description, 48) }}</small></td>
                            <td><span class="category-label">{{ $product->category->name }}</span></td>
                            <td>{{ $product->supplier->name }}</td>
                            <td>{{ $product->section->name }}</td>
                            <td class="fw-semibold text-nowrap">S/ {{ number_format((float) $product->price, 2) }}</td>
                            <td><span class="stock-badge {{ $product->stock < 5 ? 'stock-low' : 'stock-ok' }}">{{ $product->stock }} unidades</span>@if($product->stock < 5)<small class="stock-caption">Stock bajo</small>@endif</td>
                            <td><div class="table-actions">
                                <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn btn-sm btn-light action-movement" aria-label="Registrar movimiento para {{ $product->name }}" title="Registrar movimiento"><i class="bi bi-arrow-left-right"></i></a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-light action-edit" aria-label="Editar {{ $product->name }}" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Eliminar el producto {{ $product->name }}? Esta acción no se puede deshacer.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light action-delete" type="submit" aria-label="Eliminar {{ $product->name }}" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $products->links('pagination::bootstrap-5') }}</div>
        @else
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-boxes"></i></div><h4>{{ request('search') || request('category_id') || request('section_id') || request('stock') ? 'No se encontraron productos' : 'Tu inventario está vacío' }}</h4><p>{{ request('search') || request('category_id') || request('section_id') || request('stock') ? 'Cambia o limpia los filtros para ver otros resultados.' : 'Agrega categorías, proveedores y secciones para comenzar a registrar productos.' }}</p>@unless(request('search') || request('category_id') || request('section_id') || request('stock'))<a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Registrar producto</a>@endunless</div>
        @endif
    </section>
@endsection
