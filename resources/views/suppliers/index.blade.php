@extends('layouts.app')
@section('title', 'Proveedores')
@section('page-title', 'Proveedores')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">RED DE ABASTECIMIENTO</div><p>Administra los contactos de tus proveedores.</p></div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Nuevo proveedor</a>
    </div>
    <section class="content-card">
        <div class="toolbar">
            <div><h3 class="mb-1">Listado de proveedores</h3><div class="text-muted small">{{ $suppliers->total() }} registros encontrados</div></div>
            <form action="{{ route('suppliers.index') }}" method="GET" class="search-form">
                <label class="visually-hidden" for="search">Buscar proveedor</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar proveedor..."><button class="btn btn-outline-secondary" type="submit">Buscar</button></div>
            </form>
        </div>
        @if ($suppliers->isNotEmpty())
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th>Proveedor</th><th>Correo</th><th>Teléfono</th><th>Productos</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td><div class="fw-semibold">{{ $supplier->name }}</div><small class="text-muted">{{ $supplier->address ?: 'Sin dirección registrada' }}</small></td>
                            <td>{{ $supplier->email ?: '—' }}</td><td>{{ $supplier->phone ?: '—' }}</td>
                            <td><span class="count-pill">{{ $supplier->products_count }}</span></td>
                            <td><div class="table-actions">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-light action-edit" aria-label="Editar {{ $supplier->name }}" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('¿Eliminar el proveedor {{ $supplier->name }}? No se puede eliminar si tiene productos asociados.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light action-delete" type="submit" aria-label="Eliminar {{ $supplier->name }}" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $suppliers->links('pagination::bootstrap-5') }}</div>
        @else
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-truck"></i></div><h4>{{ request('search') ? 'No se encontraron proveedores' : 'Todavía no hay proveedores' }}</h4><p>{{ request('search') ? 'Prueba con otro término de búsqueda.' : 'Registra tus proveedores para asociarlos con los productos.' }}</p>@unless(request('search'))<a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Crear proveedor</a>@endunless</div>
        @endif
    </section>
@endsection
