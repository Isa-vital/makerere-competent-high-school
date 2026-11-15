<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'role', 'category', 'photo', 'bio', 'quote',
        'email', 'phone', 'extra_info', 'sort_order', 'status',
    ];

    protected $casts = ['sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeCategory($q, string $c) { return $q->where('category', $c); }
}
