<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCombination extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'subjects', 'faculty', 'sort_order', 'status'];

    protected $casts = ['sort_order' => 'integer'];

    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeFaculty($q, string $f) { return $q->where('faculty', $f); }
}
