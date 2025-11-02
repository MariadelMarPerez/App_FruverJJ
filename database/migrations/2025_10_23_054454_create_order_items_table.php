<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Esta migración crea la tabla 'order_items' (los productos de un pedido).
 * Es una tabla 100% nueva y no afecta a tu tabla 'sale_details'.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Relaciona este item con un pedido de la tabla 'orders'
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Si se borra el pedido, se borran los items
            
            // Relaciona este item con un producto de tu tabla 'products'
            $table->foreignId('product_id')->constrained('products'); 
            
            // Usamos decimal para que coincida con tu lógica de stock (para Kg)
            $table->decimal('cantidad', 10, 2); 
            $table->decimal('precio_unitario', 10, 2); // El precio al que se compró
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

