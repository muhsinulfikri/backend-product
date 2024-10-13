<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_product';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'description'
    ];
    public function products() {
        return $this->hasMany(Product::class);
    }
}
