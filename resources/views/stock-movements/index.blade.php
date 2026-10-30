@extends('layouts.app')
@section('title', 'Movimientos de stock')
@section('page-title', 'Movimientos de stock')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">TRAZABILIDAD DE INVENTARIO</div><p>Consulta las entradas, salidas y ajustes de existencias.</p></div>
        <a href="{{ route('stock-movements.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Registrar movimiento</a>
    </div>
    <section class="content-card">
        <div class="toolbar">
            <div><h3 class="mb-1">Historial de movimientos</h3><div class="text-muted small">{{ $movements->total() }} registros encontrados</div></div>
            <form action="{{ route('stock-movements.index') }}" method="GET" class="filter-form">
                <label class="visually-hidden" for="search">Buscar producto</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-search"></i></span><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar producto..."></div>
                <label class="visually-hidden" for="type">Filtrar por movimiento</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Todos los movimientos</option>
                    <option value="initial" @selected(request('type') === 'initial')>Saldo inicial</option>
                    <option value="entry" @selected(request('type') === 'entry')>Entradas</option>
                    <option value="exit" @selected(request('type') === 'exit')>Salidas</option>
                    <option value="adjustment" @selected(request('type') === 'adjustment')>Ajustes</option>
                </select>
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                @if (request()->hasAny(['search', 'type']))<a href="{{ route('stock-movements.index') }}" class="btn btn-light" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>@endif
            </form>
        </div>
        @if ($movements->isNotEmpty())
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead><tr><th>Fecha</th><th>Producto</th><th>Movimiento</th><th>Cantidad</th><th>Saldo anterior</th><th>Saldo nuevo</th><th>Detalle</th></tr></thead>
                    <tbody>
                    @foreach ($movements as $movement)
                        @php
                            $movementLabel = match ($movement->type) {
                                'initial' => 'Saldo inicial',
                                'entry' => 'Entrada',
                                'exit' => 'Salida',
                                'adjustment' => 'Ajuste',
                                default => 'Movimiento',
                            };
                        @endphp
                        <tr>
                            <td class="text-nowrap"><div class="fw-semibold">{{ $movement->created_at->format('d/m/Y') }}</div><small class="text-muted">{{ $movement->created_at->format('H:i') }}</small></td>
                            <td class="fw-semibold">{{ $movement->product?->name ?? $movement->product_name }}@unless($movement->product)<small class="d-block text-muted">Producto eliminado</small>@endunless</td>
                            <td><span class="movement-badge movement-{{ $movement->type }}">{{ $movementLabel }}</span></td>
                            <td class="fw-semibold">{{ $movement->quantity }} uds.</td>
                            <td>{{ $movement->stock_before }} uds.</td>
                            <td class="fw-semibold">{{ $movement->stock_after }} uds.</td>
                            <td class="text-muted">{{ $movement->notes ?: '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $movements->links('pagination::bootstrap-5') }}</div>
        @else
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-arrow-left-right"></i></div><h4>{{ request('search') || request('type') ? 'No se encontraron movimientos' : 'Todavía no hay movimientos' }}</h4><p>{{ request('search') || request('type') ? 'Cambia o limpia los filtros para ver otros resultados.' : 'Registra una entrada o salida para actualizar las existencias y empezar el historial.' }}</p>@unless(request('search') || request('type'))<a href="{{ route('stock-movements.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Registrar movimiento</a>@endunless</div>
        @endif
    </section>
@endsection
