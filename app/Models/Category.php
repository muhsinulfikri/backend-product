<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_category';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_category',
        'name',
        'description'
    ];
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
