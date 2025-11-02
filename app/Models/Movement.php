<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = ['tipo', 'product_id', 'cantidad', 'fecha', 'motivo'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
