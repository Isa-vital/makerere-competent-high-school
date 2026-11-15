<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'status',
        'ip_address',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at'   => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }
}
