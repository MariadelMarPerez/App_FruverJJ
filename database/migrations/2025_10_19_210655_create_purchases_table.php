<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('providers');
            $table->foreignId('user_id')->constrained('users'); // Quién la registró
            $table->decimal('total_cost', 10, 2);
            $table->string('status')->default('completada'); // Para anular (completada, anulada)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};