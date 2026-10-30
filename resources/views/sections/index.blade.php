@extends('layouts.app')
@section('title', 'Secciones')
@section('page-title', 'Secciones')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">UBICACIONES DE INVENTARIO</div><p>Organiza los productos por almacén, vitrina o área.</p></div>
        <a href="{{ route('sections.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Nueva sección</a>
    </div>
    <section class="content-card">
        <div class="toolbar">
            <div><h3 class="mb-1">Listado de secciones</h3><div class="text-muted small">{{ $sections->total() }} registros encontrados</div></div>
            <form action="{{ route('sections.index') }}" method="GET" class="search-form">
                <label class="visually-hidden" for="search">Buscar sección</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar sección..."><button class="btn btn-outline-secondary" type="submit">Buscar</button></div>
            </form>
        </div>
        @if ($sections->isNotEmpty())
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th>Sección</th><th>Descripción</th><th>Productos</th><th>Fecha de registro</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                    @foreach ($sections as $section)
                        <tr>
                            <td class="fw-semibold">{{ $section->name }}</td>
                            <td class="text-muted">{{ $section->description ?: '—' }}</td>
                            <td><span class="count-pill">{{ $section->products_count }}</span></td>
                            <td>{{ $section->created_at->format('d/m/Y') }}</td>
                            <td><div class="table-actions">
                                <a href="{{ route('sections.edit', $section) }}" class="btn btn-sm btn-light action-edit" aria-label="Editar {{ $section->name }}" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('sections.destroy', $section) }}" method="POST" onsubmit="return confirm('¿Eliminar la sección {{ $section->name }}? No se puede eliminar si tiene productos asociados.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light action-delete" type="submit" aria-label="Eliminar {{ $section->name }}" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $sections->links('pagination::bootstrap-5') }}</div>
        @else
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-geo-alt"></i></div><h4>{{ request('search') ? 'No se encontraron secciones' : 'Todavía no hay secciones' }}</h4><p>{{ request('search') ? 'Prueba con otro término de búsqueda.' : 'Crea secciones para indicar dónde se almacenan tus productos.' }}</p>@unless(request('search'))<a href="{{ route('sections.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Crear sección</a>@endunless</div>
        @endif
    </section>
@endsection
