<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'category',
        'featured_image',
        'status',
        'registration_required',
        'max_participants',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date'            => 'date',
            'registration_required' => 'boolean',
            'max_participants'      => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
