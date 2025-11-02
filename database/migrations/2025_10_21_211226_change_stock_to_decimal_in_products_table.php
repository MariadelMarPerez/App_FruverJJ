<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Import DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     * Cambia la columna stock a decimal para permitir Kg con decimales.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Asegúrate de tener instalado doctrine/dbal: composer require doctrine/dbal
            // Permite hasta 10 dígitos, 2 decimales (ej: 12345678.90), sin signo negativo
            $table->decimal('stock', 10, 2)->unsigned()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     * Vuelve a cambiar la columna stock a integer.
     * ¡Cuidado! Puede causar pérdida de datos si hay decimales.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revertir a integer. Puede perder decimales.
             DB::statement('ALTER TABLE products MODIFY stock INT UNSIGNED DEFAULT 0');
            // $table->integer('stock')->unsigned()->default(0)->change(); // Alternativa que podría fallar
        });
    }
};
