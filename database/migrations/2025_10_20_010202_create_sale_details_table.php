<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla detalle de las ventas.
     * Cada registro representa un producto dentro de una venta.
     */
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id(); // id del detalle
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade'); 
            // Relación con la tabla sales: si se borra la venta, se borran sus detalles
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            // Relación con tabla products (ya existente)
            $table->integer('cantidad')->unsigned(); // cuántas unidades se vendieron
            $table->decimal('precio_unitario', 12, 2); // precio por unidad
            $table->decimal('subtotal', 12, 2); // cantidad * precio_unitario
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};