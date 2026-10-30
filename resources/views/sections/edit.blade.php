@extends('layouts.app')
@section('title', 'Editar sección')
@section('page-title', 'Editar sección')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">UBICACIONES DE INVENTARIO</div><p>Actualiza la información de {{ $section->name }}.</p></div><a href="{{ route('sections.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Editar sección</h3><p class="text-muted mb-0">Modifica los datos de la ubicación.</p></div></div>
        <form action="{{ route('sections.update', $section) }}" method="POST">@csrf @method('PUT') @include('sections._form', ['submitLabel' => 'Guardar cambios'])</form>
    </section>
@endsection
