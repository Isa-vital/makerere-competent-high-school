<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $disk = Storage::disk('public');

        foreach (config('site_content', []) as $category => $rows) {
            foreach ($rows as $key => $definition) {
                [$value, $label] = $definition;
                $type = $definition[2] ?? 'text';
                $value = match ($key) {
                    'site_title' => config('school.name', $value),
                    'contact_email' => config('school.email', $value),
                    'contact_phone' => config('school.phone', $value),
                    'contact_address' => config('school.address', $value),
                    'facebook_url' => config('school.social.facebook', $value),
                    'instagram_url' => config('school.social.instagram', $value),
                    'twitter_url' => config('school.social.twitter', $value),
                    'youtube_url' => config('school.social.youtube', $value),
                    default => $value,
                };
                // The database enum uses "text" for image paths; the CMS renders
                // image keys as upload fields without changing existing schemas.
                $storedType = $type === 'image' ? 'text' : $type;

                if ($type === 'image' && ! $disk->exists($value)) {
                    $filename = str_contains($key, 'logo') ? 'competentlogo.jpeg' : 'gate.jpeg';
                    $source = public_path('assets/images/' . $filename);
                    if (is_file($source)) {
                        $disk->put($value, file_get_contents($source));
                    }
                }

                SiteSetting::firstOrCreate(['setting_key' => $key], [
                    'setting_value' => $value,
                    'setting_type' => $storedType,
                    'category' => $category,
                    'description' => $label,
                ]);
            }
        }
    }
}
