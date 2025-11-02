<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo para la tabla 'orders'.
 * Este modelo define los campos y las relaciones del pedido de un cliente.
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Estos deben coincidir con las columnas de tu migración 'orders'.
     */
    protected $fillable = [
        'user_id',
        'direccion_envio',
        'total',
        'metodo_pago',
        'referencia_pago',
        'monto_efectivo',
        'estado_pedido',
        'estado_pago',
    ];

    /**
     * Define la relación: Un pedido (Order) pertenece a un Usuario (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define la relación: Un pedido (Order) tiene muchos Items (OrderItem).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

