<?php

namespace Tests\Feature;

use App\Filament\Resources\AdmissionInquiryResource;
use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CmsWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_06_03_045412_create_permission_tables.php',
            '--force' => true,
        ]);

        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value')->nullable();
            $table->string('setting_type');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->string('status');
            $table->integer('sort_order')->default(0);
        });

        Schema::create('leaderships', function (Blueprint $table): void {
            $table->id();
            $table->string('status');
            $table->string('category');
            $table->integer('sort_order')->default(0);
        });

        Schema::create('gallery', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('image_path');
            $table->string('category');
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('status');
            $table->timestamps();
        });
    }

    public function test_admission_inquiries_are_visible_in_their_cms_resource(): void
    {
        $this->post('/admissions', [
            'name' => 'Parent Example',
            'email' => 'parent@example.test',
            'student_name' => 'Student Example',
            'desired_class' => 'S1',
            'inquiry_type' => 'General Admission Information',
        ])->assertRedirect();

        $this->assertSame(1, ContactMessage::count());
        $this->assertSame(1, AdmissionInquiryResource::getEloquentQuery()->count());
        $this->assertSame(0, ContactMessageResource::getEloquentQuery()->count());

        ContactMessage::create([
            'name' => 'General Example',
            'email' => 'general@example.test',
            'subject' => 'General question',
            'message' => 'Hello',
            'status' => 'new',
        ]);

        $this->assertSame(1, AdmissionInquiryResource::getEloquentQuery()->count());
        $this->assertSame(1, ContactMessageResource::getEloquentQuery()->count());
    }

    public function test_public_school_details_follow_cms_settings_updates(): void
    {
        $phone = SiteSetting::create([
            'setting_key' => 'contact_phone',
            'setting_value' => '+256 111 111111',
            'setting_type' => 'text',
        ]);

        $this->assertSame('+256 111 111111', school_detail('phone'));

        $phone->update(['setting_value' => '+256 222 222222']);

        $this->assertSame('+256 222 222222', school_detail('phone'));
        $this->get('/contact')->assertOk()->assertSee('+256 222 222222');
    }

    public function test_gallery_renders_a_cms_photo_with_a_real_public_file(): void
    {
        $imagePath = 'gallery_20250718124224_SdnXVE.jpeg';
        $this->assertNotNull(school_image_url($imagePath));

        Gallery::create([
            'title' => 'School entrance',
            'image_path' => $imagePath,
            'category' => 'facilities',
            'status' => 'active',
        ]);

        $this->get('/gallery')->assertOk()->assertSee('School entrance');
    }

    public function test_editor_cannot_manage_users_but_admin_can(): void
    {
        // The database defaults the legacy role column to admin, even for Spatie editors.
        $editor = new User(['name' => 'Editor', 'role' => 'admin', 'status' => 'active']);
        $editor->setRelation('roles', collect([new Role(['name' => 'editor', 'guard_name' => 'web'])]));

        $admin = new User(['name' => 'Admin', 'role' => 'admin', 'status' => 'active']);
        $admin->setRelation('roles', collect());
        $spatieAdmin = new User(['name' => 'Spatie Admin', 'role' => 'admin', 'status' => 'active']);
        $spatieAdmin->setRelation('roles', collect([new Role(['name' => 'admin', 'guard_name' => 'web'])]));
        $record = new User(['name' => 'Other user']);

        foreach (['viewAny', 'create', 'deleteAny'] as $ability) {
            $this->assertFalse(Gate::forUser($editor)->allows($ability, User::class));
            $this->assertTrue(Gate::forUser($admin)->allows($ability, User::class));
            $this->assertTrue(Gate::forUser($spatieAdmin)->allows($ability, User::class));
        }

        foreach (['view', 'update', 'delete'] as $ability) {
            $this->assertFalse(Gate::forUser($editor)->allows($ability, $record));
            $this->assertTrue(Gate::forUser($admin)->allows($ability, $record));
            $this->assertTrue(Gate::forUser($spatieAdmin)->allows($ability, $record));
        }
    }
}
