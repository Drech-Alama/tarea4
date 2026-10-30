@extends('layouts.app')
@section('title', 'Mi perfil')
@section('page-title', 'Mi perfil')

@section('content')
    <div class="page-intro">
        <div><div class="eyebrow">CUENTA PERSONAL</div><p>Administra tus datos de acceso y la información de tu cuenta.</p></div>
    </div>

    <div class="row g-4 profile-layout">
        <div class="col-12 col-xl-4">
            <section class="content-card profile-summary-card">
                <div class="profile-summary-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}</div>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <span class="profile-status"><span class="status-dot"></span> Cuenta activa</span>
                <div class="profile-summary-divider"></div>
                <div class="profile-detail-row"><i class="bi bi-calendar3"></i><span>Miembro desde</span><strong>{{ $user->created_at?->format('d/m/Y') ?? '—' }}</strong></div>
                <div class="profile-detail-row"><i class="bi bi-shield-lock"></i><span>Acceso protegido</span><strong>Contraseña</strong></div>
            </section>
        </div>

        <div class="col-12 col-xl-8">
            <section class="content-card profile-section-card mb-4">
                <div class="card-heading">
                    <div><div class="eyebrow">INFORMACIÓN PERSONAL</div><h3>Datos de la cuenta</h3><p class="text-muted mb-0">Actualiza el nombre y correo asociados a tu acceso.</p></div>
                    <div class="profile-section-icon"><i class="bi bi-person-vcard"></i></div>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="name" class="form-label">Nombre completo</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" maxlength="255" autocomplete="name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" maxlength="255" autocomplete="email" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-2"></i>Guardar perfil</button></div>
                </form>
            </section>

            <section class="content-card profile-section-card">
                <div class="card-heading">
                    <div><div class="eyebrow">SEGURIDAD</div><h3>Cambiar contraseña</h3><p class="text-muted mb-0">Confirma tu contraseña actual para establecer una nueva.</p></div>
                    <div class="profile-section-icon profile-security-icon"><i class="bi bi-shield-lock"></i></div>
                </div>
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="current_password" class="form-label">Contraseña actual</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="password" class="form-label">Contraseña nueva</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" minlength="8" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña nueva</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password" minlength="8" required>
                        </div>
                    </div>
                    <div class="form-actions"><button type="submit" class="btn btn-primary"><i class="bi bi-key me-2"></i>Actualizar contraseña</button></div>
                </form>
            </section>
        </div>
    </div>
@endsection
