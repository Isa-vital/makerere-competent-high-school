<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryMilestone extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'title', 'description', 'sort_order', 'status'];

    protected $casts = ['sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('status', 'active'); }
}
