<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="login-page">
    <main class="login-layout">
        <section class="login-brand-panel">
            <a href="{{ route('login') }}" class="login-brand">
                <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
                <span><strong>Nexo Inventario</strong><small>Panel de gestión</small></span>
            </a>
            <div class="login-brand-message">
                <span class="eyebrow text-white-50">CONTROL DE INVENTARIO</span>
                <h1>Todo tu inventario,<br>en un solo lugar.</h1>
                <p>Gestiona productos, proveedores y movimientos de stock con claridad y confianza.</p>
            </div>
            <div class="login-brand-footer"><i class="bi bi-shield-check me-2"></i>Acceso seguro para tu equipo</div>
            <div class="login-decoration"><i class="bi bi-box-seam"></i></div>
        </section>
        <section class="login-form-panel">
            <div class="login-form-wrap">
                <div class="login-mobile-brand">
                    <span class="brand-mark"><i class="bi bi-box-seam"></i></span>
                    <span><strong>Nexo Inventario</strong><small>Panel de gestión</small></span>
                </div>
                <div class="login-heading">
                    <span class="eyebrow">BIENVENIDO DE NUEVO</span>
                    <h2>Iniciar sesión</h2>
                    <p>Ingresa tus credenciales para continuar al panel.</p>
                </div>
                @if (session('success'))
                    <div class="alert alert-success app-alert"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger app-alert" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login.store') }}" class="login-form">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group login-input">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="username" required autofocus placeholder="nombre@correo.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group login-input">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required placeholder="Ingresa tu contraseña">
                        </div>
                    </div>
                    <div class="form-check login-remember">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
                        <label class="form-check-label" for="remember">Recordarme en este dispositivo</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 login-submit"><i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al sistema</button>
                </form>
                <p class="login-help"><i class="bi bi-info-circle me-1"></i>Solicita una cuenta al administrador del sistema.</p>
            </div>
            <footer class="login-footer">© {{ now()->year }} Nexo Inventario <span>·</span> SENATI</footer>
        </section>
    </main>
</body>
</html>
