<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'size_name',
        'material_id',
        'material_name',
        'print_side_id',
        'print_side_name',
        'finishing_ids',
        'finishing_names',
        'custom_width',
        'custom_height',
        'quantity',
        'unit_price',
        'total_price',
        'uploaded_file_name',
        'uploaded_file_url',
        'uploaded_file_status',
        'status',
        'notes',
    ];

    protected $casts = [
        'finishing_ids' => 'array',
        'finishing_names' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
