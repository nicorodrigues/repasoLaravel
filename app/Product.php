<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'cost', 'profit_margin', 'category_id', 'fotopath'];

    public function getPrice() {
        return $this->cost + ($this->cost * $this->profit_margin / 100);
    }

    public function category() {
        return $this->belongsTo('\App\Category', 'category_id', 'id');
    }

    public function properties() {
        return $this->belongsToMany('\App\Property', 'product_property', 'product_id', 'property_id');
    }
}
