<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_level', 'fee_type', 'amount', 'amount_secondary',
        'term', 'year', 'sort_order', 'status',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function scopeActive($q) { return $q->where('status', 'active'); }
}
