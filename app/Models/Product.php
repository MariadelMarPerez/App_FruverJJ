<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Asegúrate de tener HasFactory si lo usas
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // Añadir si usas factories

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'is_active' // <-- AÑADIDO
    ];

    // Mantenemos tus relaciones existentes
    public function purchases()
    {
        
         return $this->hasManyThrough(Purchase::class, PurchaseDetail::class); // Retorno
    }

    public function sales()
    {
      
         return $this->hasManyThrough(Sale::class, SaleDetail::class); // Retorno
    }

    
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

   
}
