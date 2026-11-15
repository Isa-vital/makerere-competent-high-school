<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'student_name',
        'parent_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'previous_school',
        'class_applying',
        'documents_path',
        'application_fee_paid',
        'status',
        'notes',
        'processed_by',
        'processed_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'        => 'date',
            'application_fee_paid' => 'boolean',
            'processed_at'         => 'datetime',
            'created_at'           => 'datetime',
        ];
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
