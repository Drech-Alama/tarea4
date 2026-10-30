@extends('layouts.app')
@section('title', 'Nuevo proveedor')
@section('page-title', 'Nuevo proveedor')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">ABASTECIMIENTO</div><p>Completa los datos de contacto del proveedor.</p></div><a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Datos del proveedor</h3><p class="text-muted mb-0">El nombre es obligatorio; los demás datos son opcionales.</p></div></div>
        <form action="{{ route('suppliers.store') }}" method="POST">@csrf @include('suppliers._form', ['submitLabel' => 'Guardar proveedor'])</form>
    </section>
@endsection
