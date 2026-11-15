<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'employee_id',
        'qualification',
        'specialization',
        'subjects_taught',
        'experience_years',
        'profile_photo',
        'bio',
        'status',
        'hire_date',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'hire_date'        => 'date',
        ];
    }
}
