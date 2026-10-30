@extends('layouts.app')
@section('title', 'Editar producto')
@section('page-title', 'Editar producto')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">INVENTARIO</div><p>Actualiza la información de {{ $product->name }}.</p></div><a href="{{ route('products.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Editar producto</h3><p class="text-muted mb-0">Ajusta los datos y guarda los cambios.</p></div></div>
        <form action="{{ route('products.update', $product) }}" method="POST">@csrf @method('PUT') @include('products._form', ['submitLabel' => 'Guardar cambios'])</form>
    </section>
@endsection
