@extends('layouts.app')
@section('title', 'Registrar movimiento')
@section('page-title', 'Registrar movimiento')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">CONTROL DE EXISTENCIAS</div><p>Registra una entrada o salida de productos.</p></div>
        <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Volver al historial</a>
    </div>
    @if ($products->isEmpty())
        <div class="alert alert-warning app-alert"><i class="bi bi-info-circle-fill me-2"></i>Primero registra un producto para poder agregar movimientos. <a href="{{ route('products.create') }}" class="alert-link">Crear producto</a></div>
    @endif
    <section class="content-card form-card">
        <div class="card-heading"><div><h3>Datos del movimiento</h3><p class="text-muted mb-0">El stock se actualizará automáticamente al guardar.</p></div></div>
        <form action="{{ route('stock-movements.store') }}" method="POST">
            @csrf
            <div class="row g-4 form-fields">
                <div class="col-12">
                    <label for="product_id" class="form-label">Producto <span class="required-mark">*</span></label>
                    <select id="product_id" name="product_id" class="form-select @error('product_id') is-invalid @enderror" required @disabled($products->isEmpty())>
                        <option value="">Selecciona un producto</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected((string) old('product_id', $selectedProductId ?: '') === (string) $product->id)>{{ $product->name }} — stock actual: {{ $product->stock }}</option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="type" class="form-label">Tipo de movimiento <span class="required-mark">*</span></label>
                    <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required @disabled($products->isEmpty())>
                        <option value="">Selecciona una operación</option>
                        <option value="entry" @selected(old('type') === 'entry')>Entrada — sumar al stock</option>
                        <option value="exit" @selected(old('type') === 'exit')>Salida — restar del stock</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="quantity" class="form-label">Cantidad <span class="required-mark">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" class="form-control @error('quantity') is-invalid @enderror" min="1" step="1" required @disabled($products->isEmpty()) placeholder="Ej. 10">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Detalle <span class="text-muted fw-normal">(opcional)</span></label>
                    <textarea id="notes" name="notes" rows="3" maxlength="2000" class="form-control @error('notes') is-invalid @enderror" @disabled($products->isEmpty()) placeholder="Ej. Compra a proveedor, venta o motivo del ajuste...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-actions"><a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">Cancelar</a><button type="submit" class="btn btn-primary" @disabled($products->isEmpty())><i class="bi bi-check2 me-2"></i>Registrar movimiento</button></div>
        </form>
    </section>
@endsection
