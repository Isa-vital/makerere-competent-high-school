<?php

use App\Models\SiteSetting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}

if (! function_exists('school_detail')) {
    /** Read editable public school details, falling back to environment configuration. */
    function school_detail(string $field): string
    {
        $settingKey = match ($field) {
            'name' => 'site_title',
            'email' => 'contact_email',
            'phone' => 'contact_phone',
            'address' => 'contact_address',
        };

        $fallback = config("school.{$field}");
        $value = setting($settingKey, $fallback);

        return is_string($value) && trim($value) !== '' ? $value : (string) $fallback;
    }
}

if (! function_exists('school_social_links')) {
    /** Use CMS social links while retaining configured defaults for missing settings. */
    function school_social_links(): array
    {
        $fallbacks = config('school.social', []);

        foreach (['facebook', 'twitter', 'instagram', 'youtube'] as $platform) {
            $fallbacks[$platform] = setting("{$platform}_url", $fallbacks[$platform] ?? '');
        }

        return $fallbacks;
    }
}

if (! function_exists('school_image_url')) {
    /** Resolve a public school upload only when the underlying file exists. */
    function school_image_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $filename = basename(str_replace('\\', '/', $path));
        $legacy = public_path('assets/images/uploads/' . $filename);
        if (is_file($legacy)) {
            return asset('assets/images/uploads/' . $filename);
        }

        $relative = ltrim(str_replace('\\', '/', $path), '/');
        if (preg_match('~^(news|gallery)/[^/]+$~', $relative) && is_file(public_path('storage/' . $relative))) {
            return asset('storage/' . $relative);
        }

        return null;
    }
}
