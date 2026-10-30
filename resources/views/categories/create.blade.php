@extends('layouts.app')
@section('title', 'Nueva categoría')
@section('page-title', 'Nueva categoría')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">CATÁLOGO</div><p>Completa los datos para crear una categoría.</p></div><a href="{{ route('categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Datos de la categoría</h3><p class="text-muted mb-0">Los campos marcados con * son obligatorios.</p></div></div>
        <form action="{{ route('categories.store') }}" method="POST">@csrf @include('categories._form', ['submitLabel' => 'Guardar categoría'])</form>
    </section>
@endsection
