<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para la tabla 'order_items'.
 * Define los productos individuales dentro de un pedido.
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Estos deben coincidir con las columnas de tu migración 'order_items'.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'cantidad',
        'precio_unitario',
    ];

    /**
     * Define la relación: Un item (OrderItem) pertenece a un Pedido (Order).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
* Define la relación: Un item (OrderItem) pertenece a un Producto (Product).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

