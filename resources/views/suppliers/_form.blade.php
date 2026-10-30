<div class="row g-4 form-fields">
    <div class="col-12 col-md-6">
        <label for="name" class="form-label">Nombre del proveedor <span class="required-mark">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" maxlength="150" required autofocus placeholder="Ej. Distribuidora Tech Perú">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="email" class="form-label">Correo electrónico <span class="text-muted fw-normal">(opcional)</span></label>
        <input type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" maxlength="255" placeholder="contacto@empresa.com">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="phone" class="form-label">Teléfono <span class="text-muted fw-normal">(opcional)</span></label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" maxlength="30" placeholder="+51 999 999 999">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="address" class="form-label">Dirección <span class="text-muted fw-normal">(opcional)</span></label>
        <input type="text" id="address" name="address" value="{{ old('address', $supplier->address ?? '') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Ciudad, distrito o dirección">
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-actions"><a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-2"></i>{{ $submitLabel }}</button></div>
