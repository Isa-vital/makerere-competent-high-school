<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSubject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'level', 'category', 'sort_order', 'status'];

    protected $casts = ['sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeLevel($q, string $l) { return $q->where('level', $l); }
}
