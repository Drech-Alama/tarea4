<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DemoInventorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Periféricos', 'description' => 'Dispositivos de entrada y salida para computadoras.'],
            ['name' => 'Componentes', 'description' => 'Partes y componentes para equipos informáticos.'],
            ['name' => 'Accesorios', 'description' => 'Complementos para oficina y equipos tecnológicos.'],
        ])->mapWithKeys(fn (array $data) => [
            $data['name'] => Category::firstOrCreate(['name' => $data['name']], ['description' => $data['description']]),
        ]);

        $suppliers = collect([
            ['name' => 'Distribuidora Tech Perú', 'email' => 'ventas@techperu.example', 'phone' => '+51 1 555 0142', 'address' => 'Cercado de Lima'],
            ['name' => 'CompuMarket Lima', 'email' => 'pedidos@compumarket.example', 'phone' => '+51 1 555 0186', 'address' => 'Miraflores, Lima'],
        ])->mapWithKeys(fn (array $data) => [
            $data['name'] => Supplier::firstOrCreate(['name' => $data['name']], $data),
        ]);

        $sections = collect([
            ['name' => 'Almacén principal', 'description' => 'Existencias principales del negocio.'],
            ['name' => 'Vitrina de exhibición', 'description' => 'Productos disponibles para exhibición y venta directa.'],
            ['name' => 'Bodega', 'description' => 'Productos de reserva y reposición.'],
        ])->mapWithKeys(fn (array $data) => [
            $data['name'] => Section::firstOrCreate(['name' => $data['name']], ['description' => $data['description']]),
        ]);

        $products = [
            ['name' => 'Teclado mecánico RGB', 'category' => 'Periféricos', 'supplier' => 'Distribuidora Tech Perú', 'section' => 'Vitrina de exhibición', 'price' => 189.90, 'stock' => 12, 'description' => 'Teclado mecánico con retroiluminación RGB.'],
            ['name' => 'Mouse inalámbrico ergonómico', 'category' => 'Periféricos', 'supplier' => 'CompuMarket Lima', 'section' => 'Almacén principal', 'price' => 79.50, 'stock' => 3, 'description' => 'Mouse óptico inalámbrico con diseño ergonómico.'],
            ['name' => 'Memoria RAM DDR4 16 GB', 'category' => 'Componentes', 'supplier' => 'Distribuidora Tech Perú', 'section' => 'Bodega', 'price' => 145.00, 'stock' => 8, 'description' => 'Módulo de memoria DDR4 de 16 GB.'],
            ['name' => 'Hub USB-C de 6 puertos', 'category' => 'Accesorios', 'supplier' => 'CompuMarket Lima', 'section' => 'Almacén principal', 'price' => 99.90, 'stock' => 2, 'description' => 'Adaptador multipuerto USB-C compacto.'],
            ['name' => 'Audífonos con micrófono', 'category' => 'Periféricos', 'supplier' => 'Distribuidora Tech Perú', 'section' => 'Vitrina de exhibición', 'price' => 119.00, 'stock' => 15, 'description' => 'Audífonos estéreo con micrófono integrado.'],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name']],
                [
                    'category_id' => $categories[$data['category']]->id,
                    'supplier_id' => $suppliers[$data['supplier']]->id,
                    'section_id' => $sections[$data['section']]->id,
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                    'description' => $data['description'],
                ],
            );
        }
    }
}
