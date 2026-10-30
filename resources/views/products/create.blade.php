@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('page-title', 'Nuevo producto')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">INVENTARIO</div><p>Registra un accesorio y sus existencias.</p></div><a href="{{ route('products.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    @if ($categories->isEmpty() || $suppliers->isEmpty() || $sections->isEmpty())
        <div class="alert alert-warning app-alert"><i class="bi bi-info-circle-fill me-2"></i>Para registrar productos primero necesitas al menos una categoría, un proveedor y una sección.
            @if($categories->isEmpty())<a href="{{ route('categories.create') }}" class="alert-link ms-1">Crear categoría</a>@endif
            @if($categories->isEmpty() && $suppliers->isEmpty())<span> · </span>@endif
            @if($suppliers->isEmpty())<a href="{{ route('suppliers.create') }}" class="alert-link">Crear proveedor</a>@endif
            @if($sections->isEmpty())<a href="{{ route('sections.create') }}" class="alert-link {{ $categories->isEmpty() || $suppliers->isEmpty() ? 'ms-1' : '' }}">Crear sección</a>@endif
        </div>
    @endif
    <section class="content-card form-card"><div class="card-heading"><div><h3>Información del producto</h3><p class="text-muted mb-0">Completa los detalles del producto.</p></div></div>
        <form action="{{ route('products.store') }}" method="POST">@csrf @include('products._form', ['submitLabel' => 'Guardar producto'])</form>
    </section>
@endsection
