<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        DB::table('sections')->insert([
            'name' => 'Almacén general',
            'description' => 'Sección asignada a los productos existentes al habilitar el módulo de secciones.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $defaultSectionId = DB::table('sections')->where('name', 'Almacén general')->value('id');

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->constrained()->restrictOnDelete();
        });

        DB::table('products')->whereNull('section_id')->update(['section_id' => $defaultSectionId]);

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('section_id');
        });

        Schema::dropIfExists('sections');
    }
};
