<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla principal de las ventas.
     * Contiene la información general de la venta y el pago.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained('users'); // Quién registró la venta
            $table->string('cliente')->nullable(); // nombre del cliente (opcional)
            $table->decimal('total', 12, 2); // total final de la venta 

        
            $table->enum('estado', ['pendiente', 'pagada', 'cancelada'])->default('pagada'); // Cambiamos default a 'pagada' ya que el proceso incluirá pago
            $table->enum('metodo_pago', ['efectivo', 'transferencia'])->nullable(); // Forma de pago
            $table->decimal('monto_recibido', 12, 2)->nullable(); // Si es efectivo, cuánto dio el cliente
            $table->decimal('cambio', 12, 2)->nullable(); // Si es efectivo, cuánto se devolvió
            $table->string('referencia_transferencia')->nullable(); // Si es transferencia, el ID

            $table->timestamps(); // created_at será la fecha/hora de la venta
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};