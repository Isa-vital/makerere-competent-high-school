<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'full_name',
        'maiden_name',
        'email',
        'phone',
        'graduation_year',
        'class_level',
        'course_studied',
        'current_profession',
        'current_employer',
        'location',
        'linkedin_profile',
        'bio',
        'profile_photo',
        'achievements',
        'willing_to_mentor',
        'willing_to_speak',
        'willing_to_donate',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'graduation_year'   => 'integer',
            'willing_to_mentor' => 'boolean',
            'willing_to_speak'  => 'boolean',
            'willing_to_donate' => 'boolean',
        ];
    }
}
