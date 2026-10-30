@extends('layouts.app')
@section('title', 'Editar categoría')
@section('page-title', 'Editar categoría')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">CATÁLOGO</div><p>Actualiza la información de {{ $category->name }}.</p></div><a href="{{ route('categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Editar categoría</h3><p class="text-muted mb-0">Modifica los campos que necesites.</p></div></div>
        <form action="{{ route('categories.update', $category) }}" method="POST">@csrf @method('PUT') @include('categories._form', ['submitLabel' => 'Guardar cambios'])</form>
    </section>
@endsection
