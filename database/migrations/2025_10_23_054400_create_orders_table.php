<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Esta migración crea la tabla 'orders' (Pedidos de Clientes).
 * Es una tabla 100% nueva y no afecta a tu tabla 'sales' (Ventas POS).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // --- Quién es el cliente ---
            // Se relaciona con un usuario que debe tener el rol 'Cliente'
            $table->foreignId('user_id')->constrained('users'); 
            
            // --- Dónde se entrega (Tu requisito) ---
            $table->text('direccion_envio');
            $table->decimal('total', 10, 2); // Total final del pedido
            
            // --- Cómo paga (Tu requisito) ---
            $table->enum('metodo_pago', ['transferencia', 'efectivo']);
            $table->string('referencia_pago')->nullable(); // Para el N° de transferencia
            $table->decimal('monto_efectivo', 10, 2)->nullable(); // Con cuánto paga (para el cambio/vuelta)

            // --- Estados del pedido (Tu requisito) ---
            $table->enum('estado_pedido', [
                'pendiente despacho', 
                'despachada', 
                'enviada', 
                'entregada'
            ])->default('pendiente despacho'); // El estado inicial
            
            // --- Estados del pago (Tu requisito) ---
            // Si es 'transferencia', el controlador lo pondrá 'pagada'
            // Si es 'efectivo' (contra entrega), el controlador lo pondrá 'pendiente'
            $table->enum('estado_pago', ['pendiente', 'pagada'])->default('pendiente');
            
            $table->timestamps(); // Fecha en que se hizo el pedido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

