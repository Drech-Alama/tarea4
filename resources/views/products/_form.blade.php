<div class="row g-4 form-fields">
    <div class="col-12 col-md-6">
        <label for="name" class="form-label">Nombre del producto <span class="required-mark">*</span></label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" maxlength="150" required autofocus placeholder="Ej. Teclado mecánico RGB">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="category_id" class="form-label">Categoría <span class="required-mark">*</span></label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Selecciona una categoría</option>
            @foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>@endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="supplier_id" class="form-label">Proveedor <span class="required-mark">*</span></label>
        <select id="supplier_id" name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
            <option value="">Selecciona un proveedor</option>
            @foreach ($suppliers as $supplier)<option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $product->supplier_id ?? '') === (string) $supplier->id)>{{ $supplier->name }}</option>@endforeach
        </select>
        @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-6">
        <label for="section_id" class="form-label">Sección <span class="required-mark">*</span></label>
        <select id="section_id" name="section_id" class="form-select @error('section_id') is-invalid @enderror" required>
            <option value="">Selecciona una sección</option>
            @foreach ($sections as $section)<option value="{{ $section->id }}" @selected((string) old('section_id', $product->section_id ?? '') === (string) $section->id)>{{ $section->name }}</option>@endforeach
        </select>
        @error('section_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3">
        <label for="price" class="form-label">Precio (S/) <span class="required-mark">*</span></label>
        <div class="input-group"><span class="input-group-text">S/</span><input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-control @error('price') is-invalid @enderror" min="0" step="0.01" required placeholder="0.00"></div>
        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 col-md-3">
        <label for="stock" class="form-label">Stock inicial <span class="required-mark">*</span></label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '') }}" class="form-control @error('stock') is-invalid @enderror" min="0" step="1" required placeholder="0">
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Descripción <span class="text-muted fw-normal">(opcional)</span></label>
        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Características u observaciones del producto...">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="form-actions"><a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancelar</a><button type="submit" class="btn btn-primary" @disabled($categories->isEmpty() || $suppliers->isEmpty() || $sections->isEmpty())><i class="bi bi-check2 me-2"></i>{{ $submitLabel }}</button></div>
