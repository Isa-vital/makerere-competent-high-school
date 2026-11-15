<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'program_code',
        'level',
        'description',
        'duration',
        'subjects',
        'requirements',
        'fees',
        'status',
    ];

    protected function casts(): array
    {
        return ['fees' => 'decimal:2'];
    }
}
