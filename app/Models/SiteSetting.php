<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'category',
        'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $row = static::where('setting_key', $key)->first();
            if (! $row) {
                return $default;
            }
            return match ($row->setting_type) {
                'boolean' => filter_var($row->setting_value, FILTER_VALIDATE_BOOLEAN),
                'number'  => is_numeric($row->setting_value) ? $row->setting_value + 0 : $default,
                default   => $row->setting_value,
            };
        });
    }

    protected static function booted(): void
    {
        static::saved(fn(self $m) => Cache::forget("setting:{$m->setting_key}"));
        static::deleted(fn(self $m) => Cache::forget("setting:{$m->setting_key}"));
    }
}
