<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de inventario') | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="appSidebar" aria-labelledby="sidebarLabel">
        <div class="sidebar-brand">
            <div class="brand-mark"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="brand-name" id="sidebarLabel">Portal Crud</div>
                <div class="brand-caption">Panel de gestión</div>
            </div>
            <button class="btn-close btn-close-white d-lg-none ms-auto" type="button" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Cerrar menú"></button>
        </div>
        <div class="sidebar-section">MENÚ PRINCIPAL</div>
        <nav class="sidebar-nav">
            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-boxes"></i><span>Productos</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <i class="bi bi-tags"></i><span>Categorías</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                <i class="bi bi-truck"></i><span>Proveedores</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('sections.*') ? 'active' : '' }}" href="{{ route('sections.index') }}">
                <i class="bi bi-geo-alt"></i><span>Secciones</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}" href="{{ route('stock-movements.index') }}">
                <i class="bi bi-arrow-left-right"></i><span>Movimientos de stock</span>
            </a>
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-note"><i class="bi bi-shield-check"></i><span>Inventario organizado,<br>negocio en control.</span></div>
            <div class="sidebar-footer">SENATI · Sistema de inventario</div>
        </div>
    </aside>

    <main class="main-panel">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-icon sidebar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="topbar-kicker">GESTIÓN COMERCIAL</div>
                    <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
                </div>
            </div>
            <div class="topbar-meta">
                <span class="status-dot"></span>
                <a class="topbar-profile" href="{{ route('profile.edit') }}" aria-label="Abrir mi perfil">
                    <span class="profile-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="topbar-user">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down profile-chevron"></i>
                </a>
                <span class="topbar-divider"></span>
                <span class="topbar-date">{{ now()->locale('es')->translatedFormat('d M Y') }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary logout-button"><i class="bi bi-box-arrow-right me-1"></i><span>Cerrar sesión</span></button>
                </form>
            </div>
        </header>

        <div class="page-content container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show app-alert" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show app-alert" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger app-alert" role="alert">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Revisa los datos ingresados:</div>
                    <ul class="mb-0 ps-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
        <footer class="app-footer">© {{ now()->year }} Nexo Inventario <span>·</span> Control simple para crecer mejor</footer>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
