<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'cliente', 
        'total', 
        'estado', 
        'metodo_pago', 
        'monto_recibido', 
        'cambio', 
        'referencia_transferencia'
    ];

    // Relación: Una venta tiene muchos detalles (productos)
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    // Relación: Una venta fue registrada por un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}