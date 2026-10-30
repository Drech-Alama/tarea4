<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_dashboard_shows_real_inventory_statistics(): void
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();
        Product::factory()->create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'stock' => 2,
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Productos registrados')
            ->assertSee('Stock bajo')
            ->assertSee('1');
    }

    public function test_categories_can_be_created_searched_updated_and_deleted(): void
    {
        $this->post(route('categories.store'), [
            'name' => 'Periféricos',
            'description' => 'Accesorios para computadoras.',
        ])->assertRedirect(route('categories.index'));

        $category = Category::where('name', 'Periféricos')->firstOrFail();
        $this->get(route('categories.index', ['search' => 'Periféricos']))->assertOk()->assertSee('Periféricos');

        $this->put(route('categories.update', $category), [
            'name' => 'Accesorios',
            'description' => 'Actualizada',
        ])->assertRedirect(route('categories.index'));

        $this->delete(route('categories.destroy', $category))->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_duplicate_category_name_is_rejected(): void
    {
        Category::factory()->create(['name' => 'Periféricos']);

        $this->from(route('categories.create'))
            ->post(route('categories.store'), ['name' => 'Periféricos'])
            ->assertSessionHasErrors('name');
    }

    public function test_suppliers_can_be_created_searched_updated_and_deleted(): void
    {
        $this->post(route('suppliers.store'), [
            'name' => 'Distribuidora Tech',
            'email' => 'ventas@example.com',
            'phone' => '999888777',
            'address' => 'Lima',
        ])->assertRedirect(route('suppliers.index'));

        $supplier = Supplier::where('name', 'Distribuidora Tech')->firstOrFail();
        $this->get(route('suppliers.index', ['search' => 'Distribuidora']))->assertOk()->assertSee('Distribuidora Tech');

        $this->put(route('suppliers.update', $supplier), [
            'name' => 'Distribuidora Central',
            'email' => 'central@example.com',
        ])->assertRedirect(route('suppliers.index'));

        $this->delete(route('suppliers.destroy', $supplier))->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_invalid_supplier_email_is_rejected(): void
    {
        $this->from(route('suppliers.create'))
            ->post(route('suppliers.store'), ['name' => 'Proveedor', 'email' => 'correo-invalido'])
            ->assertSessionHasErrors('email');
    }

    public function test_products_can_be_created_filtered_updated_and_deleted(): void
    {
        $category = Category::factory()->create(['name' => 'Periféricos']);
        $otherCategory = Category::factory()->create(['name' => 'Componentes']);
        $supplier = Supplier::factory()->create(['name' => 'Proveedor Demo']);
        $section = Section::factory()->create(['name' => 'Almacén de prueba']);
        $otherSection = Section::factory()->create(['name' => 'Vitrina de prueba']);

        $this->post(route('products.store'), [
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'section_id' => $section->id,
            'name' => 'Mouse USB',
            'price' => '49.90',
            'stock' => 4,
            'description' => 'Mouse óptico.',
        ])->assertRedirect(route('products.index'));

        $product = Product::where('name', 'Mouse USB')->firstOrFail();
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'initial',
            'stock_before' => 0,
            'stock_after' => 4,
        ]);
        Product::factory()->create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'name' => 'Teclado USB',
            'stock' => 10,
        ]);

        $this->get(route('products.index', ['category_id' => $category->id, 'search' => 'Mouse']))
            ->assertOk()
            ->assertSee('Mouse USB')
            ->assertSee('Proveedor Demo')
            ->assertSee('49.90');
        $this->get(route('products.index', ['category_id' => $otherCategory->id]))->assertDontSee('Mouse USB');
        $this->get(route('products.index', ['section_id' => $section->id]))
            ->assertSee('Mouse USB')
            ->assertDontSee('Teclado USB');
        $this->get(route('products.index', ['stock' => 'low']))
            ->assertSee('Mouse USB')
            ->assertDontSee('Teclado USB');

        $this->put(route('products.update', $product), [
            'category_id' => $otherCategory->id,
            'supplier_id' => $supplier->id,
            'section_id' => $otherSection->id,
            'name' => 'Mouse USB actualizado',
            'price' => '59.00',
            'stock' => 10,
        ])->assertRedirect(route('products.index'));

        $this->delete(route('products.destroy', $product))->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_validation_rejects_negative_price_invalid_relations_and_stock(): void
    {
        $this->from(route('products.create'))
            ->post(route('products.store'), [
                'category_id' => 999,
                'supplier_id' => 999,
                'section_id' => 999,
                'name' => 'Producto inválido',
                'price' => '-1',
                'stock' => '-2',
            ])
            ->assertSessionHasErrors(['category_id', 'supplier_id', 'section_id', 'price', 'stock']);
    }

    public function test_sections_can_be_created_searched_updated_and_deleted(): void
    {
        $this->post(route('sections.store'), [
            'name' => 'Almacén de prueba',
            'description' => 'Sección de prueba.',
        ])->assertRedirect(route('sections.index'));

        $section = Section::where('name', 'Almacén de prueba')->firstOrFail();
        $this->get(route('sections.index', ['search' => 'Almacén']))->assertOk()->assertSee('Almacén de prueba');

        $this->put(route('sections.update', $section), [
            'name' => 'Vitrina de prueba',
            'description' => 'Sección actualizada.',
        ])->assertRedirect(route('sections.index'));

        $this->delete(route('sections.destroy', $section))->assertRedirect(route('sections.index'));
        $this->assertDatabaseMissing('sections', ['id' => $section->id]);
    }

    public function test_sections_with_products_cannot_be_deleted(): void
    {
        $product = Product::factory()->create();

        $this->delete(route('sections.destroy', $product->section))
            ->assertRedirect(route('sections.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('sections', ['id' => $product->section_id]);
    }

    public function test_stock_entries_and_exits_update_product_and_keep_a_history(): void
    {
        $product = Product::factory()->create(['stock' => 8]);

        $this->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'type' => 'entry',
            'quantity' => 5,
            'notes' => 'Reposición de mercadería.',
        ])->assertRedirect(route('stock-movements.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 13]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'entry',
            'quantity' => 5,
            'stock_before' => 8,
            'stock_after' => 13,
        ]);

        $this->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'type' => 'exit',
            'quantity' => 4,
        ])->assertRedirect(route('stock-movements.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 9]);
        $this->get(route('stock-movements.index', ['search' => $product->name, 'type' => 'exit']))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('9 uds.');
    }

    public function test_stock_exit_cannot_exceed_available_inventory(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $this->from(route('stock-movements.create'))
            ->post(route('stock-movements.store'), [
                'product_id' => $product->id,
                'type' => 'exit',
                'quantity' => 4,
            ])
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 3]);
    }

    public function test_product_deletion_preserves_its_movement_history(): void
    {
        $product = Product::factory()->create(['stock' => 6]);
        $this->post(route('stock-movements.store'), [
            'product_id' => $product->id,
            'type' => 'entry',
            'quantity' => 2,
        ]);

        $productId = $product->id;
        $productName = $product->name;
        $this->delete(route('products.destroy', $product))->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $productId]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => null,
            'product_name' => $productName,
            'type' => 'exit',
            'stock_before' => 8,
            'stock_after' => 0,
        ]);
    }

    public function test_stock_changes_from_product_edit_are_recorded_as_adjustments(): void
    {
        $product = Product::factory()->create(['stock' => 4]);

        $this->put(route('products.update', $product), [
            'category_id' => $product->category_id,
            'supplier_id' => $product->supplier_id,
            'section_id' => $product->section_id,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => 9,
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity' => 5,
            'stock_before' => 4,
            'stock_after' => 9,
        ]);
    }

    public function test_categories_and_suppliers_with_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();
        Product::factory()->create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('error');
        $this->delete(route('suppliers.destroy', $supplier))
            ->assertRedirect(route('suppliers.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }

    public function test_category_listing_is_paginated(): void
    {
        Category::factory()->count(12)->create();

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee('pagination');
    }
}
