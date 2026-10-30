@extends('layouts.app')
@section('title', 'Categorías')
@section('page-title', 'Categorías')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">ORGANIZA TU CATÁLOGO</div><p>Administra las categorías de tus accesorios.</p></div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Nueva categoría</a>
    </div>
    <section class="content-card">
        <div class="toolbar">
            <div><h3 class="mb-1">Listado de categorías</h3><div class="text-muted small">{{ $categories->total() }} registros encontrados</div></div>
            <form action="{{ route('categories.index') }}" method="GET" class="search-form">
                <label class="visually-hidden" for="search">Buscar categoría</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar categoría..."><button class="btn btn-outline-secondary" type="submit">Buscar</button></div>
            </form>
        </div>
        @if ($categories->isNotEmpty())
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th>Nombre</th><th>Descripción</th><th>Productos</th><th>Fecha de registro</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->description ?: '—' }}</td>
                            <td><span class="count-pill">{{ $category->products_count }}</span></td>
                            <td>{{ $category->created_at->format('d/m/Y') }}</td>
                            <td><div class="table-actions">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-light action-edit" aria-label="Editar {{ $category->name }}" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Eliminar la categoría {{ $category->name }}? No se puede eliminar si tiene productos asociados.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light action-delete" type="submit" aria-label="Eliminar {{ $category->name }}" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $categories->links('pagination::bootstrap-5') }}</div>
        @else
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-tags"></i></div><h4>{{ request('search') ? 'No se encontraron categorías' : 'Todavía no hay categorías' }}</h4><p>{{ request('search') ? 'Prueba con otro término de búsqueda.' : 'Crea categorías para mantener tus productos organizados.' }}</p>@unless(request('search'))<a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Crear categoría</a>@endunless</div>
        @endif
    </section>
@endsection
