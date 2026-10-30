@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="welcome-card mb-4">
        <div>
            <div class="eyebrow text-white-50">RESUMEN GENERAL</div>
            <h2>¡Bienvenido a tu inventario!</h2>
            <p>Administra tus productos, categorías y proveedores desde un solo lugar.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-light btn-lg welcome-action"><i class="bi bi-plus-lg me-2"></i>Nuevo producto</a>
        <div class="welcome-decoration"><i class="bi bi-box-seam"></i></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="bi bi-boxes"></i></div>
                <div class="stat-label">Productos registrados</div>
                <div class="stat-value">{{ number_format($productsCount) }}</div>
                <a href="{{ route('products.index') }}" class="stat-link">Ver productos <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="stat-card">
                <div class="stat-icon icon-cyan"><i class="bi bi-tags"></i></div>
                <div class="stat-label">Categorías</div>
                <div class="stat-value">{{ number_format($categoriesCount) }}</div>
                <a href="{{ route('categories.index') }}" class="stat-link">Ver categorías <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="bi bi-truck"></i></div>
                <div class="stat-label">Proveedores</div>
                <div class="stat-value">{{ number_format($suppliersCount) }}</div>
                <a href="{{ route('suppliers.index') }}" class="stat-link">Ver proveedores <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="stat-card">
                <div class="stat-icon icon-orange"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="stat-label">Stock bajo <span class="text-muted">(menos de 5)</span></div>
                <div class="stat-value">{{ number_format($lowStockCount) }}</div>
                <a href="{{ route('products.index', ['stock' => 'low']) }}" class="stat-link">Revisar inventario <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="bi bi-geo-alt"></i></div>
                <div class="stat-label">Secciones</div>
                <div class="stat-value">{{ number_format($sectionsCount) }}</div>
                <a href="{{ route('sections.index') }}" class="stat-link">Ver secciones <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-7">
            <section class="content-card h-100">
                <div class="card-heading">
                    <div><div class="eyebrow">MOVIMIENTO</div><h3>Productos recientes</h3></div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Ver todos</a>
                </div>
                @if ($recentProducts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table app-table align-middle mb-0">
                            <thead><tr><th>Producto</th><th>Categoría</th><th>Sección</th><th>Precio</th><th>Stock</th></tr></thead>
                            <tbody>
                            @foreach ($recentProducts as $product)
                                <tr>
                                    <td><div class="fw-semibold">{{ $product->name }}</div><small class="text-muted">{{ $product->supplier->name }}</small></td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->section->name }}</td>
                                    <td class="fw-semibold">S/ {{ number_format((float) $product->price, 2) }}</td>
                                    <td><span class="stock-badge {{ $product->stock < 5 ? 'stock-low' : 'stock-ok' }}">{{ $product->stock }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state"><div class="empty-icon"><i class="bi bi-box-seam"></i></div><h4>Aún no hay productos</h4><p>Registra tu primer producto para comenzar a controlar el inventario.</p><a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Registrar producto</a></div>
                @endif
            </section>
        </div>
        <div class="col-12 col-xl-5">
            <section class="content-card h-100">
                <div class="card-heading">
                    <div><div class="eyebrow">ATENCIÓN</div><h3>Alertas de stock</h3></div>
                    <span class="badge rounded-pill text-bg-warning">{{ $lowStockCount }} productos</span>
                </div>
                @if ($lowStockProducts->isNotEmpty())
                    <div class="alert-list">
                        @foreach ($lowStockProducts as $product)
                            <div class="alert-item">
                                <div class="alert-product-icon"><i class="bi bi-box"></i></div>
                                <div class="flex-grow-1 min-width-0"><div class="fw-semibold text-truncate">{{ $product->name }}</div><small class="text-muted">{{ $product->category->name }} · {{ $product->section->name }}</small></div>
                                <span class="stock-badge stock-low">{{ $product->stock }} uds.</span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('products.index', ['stock' => 'low']) }}" class="btn btn-outline-primary w-100 mt-3">Ver productos con stock bajo</a>
                @else
                    <div class="clear-stock"><div class="clear-stock-icon"><i class="bi bi-check2-circle"></i></div><h4>Todo en orden</h4><p>No hay productos con stock por debajo de 5 unidades.</p></div>
                @endif
            </section>
        </div>
    </div>
@endsection
