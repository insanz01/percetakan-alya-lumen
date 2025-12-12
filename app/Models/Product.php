<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'images',
        'base_price',
        'sizes',
        'materials',
        'print_sides',
        'finishings',
        'quantity_tiers',
        'is_best_seller',
        'is_promo',
        'promo_percentage',
        'min_order_qty',
        'estimated_days',
        'weight_per_piece',
        'is_retail_product',
        'requires_design_file',
        'allowed_file_types',
        'max_file_size',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'materials' => 'array',
        'print_sides' => 'array',
        'finishings' => 'array',
        'quantity_tiers' => 'array',
        'allowed_file_types' => 'array',
        'is_best_seller' => 'boolean',
        'is_promo' => 'boolean',
        'is_retail_product' => 'boolean',
        'requires_design_file' => 'boolean',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
