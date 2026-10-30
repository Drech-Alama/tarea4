@extends('layouts.app')
@section('title', 'Nueva sección')
@section('page-title', 'Nueva sección')
@section('content')
    <div class="page-intro"><div><div class="eyebrow">UBICACIONES DE INVENTARIO</div><p>Crea una ubicación para organizar productos.</p></div><a href="{{ route('sections.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver</a></div>
    <section class="content-card form-card"><div class="card-heading"><div><h3>Datos de la sección</h3><p class="text-muted mb-0">Los campos marcados con * son obligatorios.</p></div></div>
        <form action="{{ route('sections.store') }}" method="POST">@csrf @include('sections._form', ['submitLabel' => 'Guardar sección'])</form>
    </section>
@endsection
