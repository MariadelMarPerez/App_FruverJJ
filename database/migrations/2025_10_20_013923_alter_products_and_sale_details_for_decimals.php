<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            // Cambiar la columna 'stock' en la tabla 'products'
            Schema::table('products', function (Blueprint $table) {
                // Usamos decimal para permitir Kg. Ej: 10,2 -> 10 Kg con 2 decimales de precisión
                $table->decimal('stock', 10, 2)->default(0)->change();
            });

            // Cambiar la columna 'cantidad' en la tabla 'sale_details'
            Schema::table('sale_details', function (Blueprint $table) {
                $table->decimal('cantidad', 10, 2)->change();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            // Volver a integer si es necesario revertir
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stock')->default(0)->change();
            });

            Schema::table('sale_details', function (Blueprint $table) {
                $table->integer('cantidad')->unsigned()->change();
            });
        }
    };
    
