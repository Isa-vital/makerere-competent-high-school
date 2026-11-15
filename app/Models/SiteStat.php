<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'stat_key', 'label', 'value', 'suffix', 'icon',
        'category', 'sort_order', 'status',
    ];

    protected $casts = ['sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeCategory($q, string $c) { return $q->where('category', $c); }
}
