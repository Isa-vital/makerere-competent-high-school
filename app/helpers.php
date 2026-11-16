<?php

use App\Models\SiteSetting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}

if (! function_exists('page_content')) {
    /** Escaped by Blade at the call site; the configuration is a pre-seed fallback. */
    function page_content(string $key): string
    {
        foreach (config('site_content', []) as $group) {
            if (isset($group[$key])) {
                return (string) setting($key, $group[$key][0]);
            }
        }

        return '';
    }
}

if (! function_exists('page_image')) {
    /** Resolve only CMS uploads or the bundled editorial images. */
    function page_image(string $key): string
    {
        $path = str_replace('\\', '/', page_content($key));
        if (preg_match('~^page-images/[A-Za-z0-9._/-]+$~', $path)
            && ! str_contains($path, '..')
            && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        $fallback = str_contains($key, 'logo') ? 'competentlogo.jpeg' : 'gate.jpeg';

        return asset('assets/images/' . $fallback);
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
