<div class="row g-4 form-fields">
    <div class="col-12">
        <label for="name" class="form-label">Nombre de la sección <span class="required-mark">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $section->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" maxlength="100" required autofocus placeholder="Ej. Almacén principal">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Descripción <span class="text-muted fw-normal">(opcional)</span></label>
        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Describe la ubicación de esta sección...">{{ old('description', $section->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-actions"><a href="{{ route('sections.index') }}" class="btn btn-outline-secondary">Cancelar</a><button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-2"></i>{{ $submitLabel }}</button></div>
