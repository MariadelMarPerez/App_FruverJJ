<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Importar DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     * Cambia la columna quantity a decimal para permitir Kg con decimales.
     */
    public function up(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            // Usamos change() para modificar la columna existente.
            // Permite hasta 10 dígitos en total, con 2 decimales (ej: 12345678.90).
            // Asegúrate de tener instalado doctrine/dbal: composer require doctrine/dbal
            $table->decimal('quantity', 10, 2)->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     * Vuelve a cambiar la columna quantity a integer.
     * ¡Cuidado! Si ya tienes datos con decimales, esto podría causar pérdida de datos al redondear.
     */
    public function down(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
             // Usamos un statement SQL crudo para intentar revertir sin errores de doctrine si hay decimales,
             // aunque igualmente puede haber pérdida de precisión/datos.
             DB::statement('ALTER TABLE purchase_details MODIFY quantity INT UNSIGNED');
             // $table->integer('quantity')->unsigned()->change(); // Alternativa que podría fallar
        });
    }
};

