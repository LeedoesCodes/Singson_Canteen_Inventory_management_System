<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    protected $fillable = [
        'product_id',
        'previous_stock',
        'new_stock',
        'quantity_change',
        'type',
        'reference_type',
        'reference_id',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'previous_stock' => 'integer',
        'new_stock' => 'integer',
        'quantity_change' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get the reference model (polymorphic relationship)
    public function reference()
    {
        return $this->morphTo();
    }
}