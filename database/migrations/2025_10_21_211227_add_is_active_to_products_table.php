<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade la columna is_active para borrado lógico.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Por defecto, todos los productos estarán activos (true)
            // La colocamos después de 'stock' para orden
            $table->boolean('is_active')->default(true)->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     * Elimina la columna is_active.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
