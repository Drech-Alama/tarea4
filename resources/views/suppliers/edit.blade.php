@extends('layouts.app')
@section('title', 'Editar proveedor')
@section('page-title', 'Editar proveedor')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">ABASTECIMIENTO</div><p>Actualiza la información de {{ $supplier->name }}.</p></div><a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Editar proveedor</h3><p class="text-muted mb-0">Modifica los datos de contacto registrados.</p></div></div>
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">@csrf @method('PUT') @include('suppliers._form', ['submitLabel' => 'Guardar cambios'])</form>
    </section>
@endsection
