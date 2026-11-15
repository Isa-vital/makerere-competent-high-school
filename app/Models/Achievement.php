<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'value', 'description', 'icon', 'color',
        'category', 'sort_order', 'status',
    ];

    protected $casts = ['sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeCategory($q, string $c) { return $q->where('category', $c); }
}
