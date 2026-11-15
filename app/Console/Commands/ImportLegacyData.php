<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class ImportLegacyData extends Command
{
    protected $signature = 'import:legacy
        {--host=127.0.0.1}
        {--port=3308}
        {--database=school_db}
        {--username=root}
        {--password=Today123}
        {--truncate : Wipe target tables before importing}';

    protected $description = 'Import rows from the live procedural school_db into the new Laravel schema.';

    public function handle(): int
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $this->option('host'),
            $this->option('port'),
            $this->option('database')
        );

        try {
            $legacy = new PDO($dsn, $this->option('username'), $this->option('password'), [
                PDO::ATTR_ERRMODE         => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (\Throwable $e) {
            $this->error('Cannot connect to legacy DB: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("Connected to legacy DB: {$this->option('database')}");

        if ($this->option('truncate')) {
            $this->warn('Truncating target tables...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach (['contact_messages', 'activity_log', 'admissions', 'alumni', 'events', 'gallery', 'news', 'site_settings', 'users'] as $t) {
                DB::table($t)->truncate();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $totals = [];
        $totals['users']            = $this->importUsers($legacy);
        $totals['site_settings']    = $this->importSiteSettings($legacy);
        $totals['news']             = $this->importNews($legacy);
        $totals['gallery']          = $this->importGallery($legacy);
        $totals['events']           = $this->importEvents($legacy);
        $totals['alumni']           = $this->importAlumni($legacy);
        $totals['admissions']       = $this->importAdmissions($legacy);
        $totals['contact_messages'] = $this->importContactMessages($legacy);
        $totals['activity_log']     = $this->importActivityLog($legacy);

        $this->newLine();
        $this->info('Import complete:');
        foreach ($totals as $table => $n) {
            $this->line(sprintf('  %-20s %d rows', $table, $n));
        }
        return self::SUCCESS;
    }

    private function importUsers(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM admin_users')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('users')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'username'   => $r['username'],
                    'name'       => $r['full_name'] ?? $r['username'],
                    'email'      => $r['email'],
                    'password'   => $r['password'], // already hashed in legacy
                    'role'       => $r['role'] === 'editor' ? 'admin' : $r['role'],
                    'status'     => $r['status'],
                    'last_login' => $r['last_login'],
                    'created_at' => $r['created_at'],
                    'updated_at' => $r['created_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importSiteSettings(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM site_settings')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $r['setting_key']],
                [
                    'setting_value' => $r['setting_value'],
                    'setting_type'  => 'text',
                    'category'      => 'general',
                    'description'   => $r['description'],
                    'updated_at'    => $r['updated_at'],
                    'created_at'    => $r['updated_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importNews(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM news')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('news')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'title'          => $r['title'],
                    'slug'           => $r['slug'] ?: \Illuminate\Support\Str::slug($r['title']) . '-' . $r['id'],
                    'content'        => $r['content'],
                    'excerpt'        => $r['excerpt'],
                    'featured_image' => $r['featured_image'],
                    'category'       => $r['category'],
                    'tags'           => $r['tags'],
                    'author_id'      => $r['author_id'] ?: null,
                    'status'         => $r['status'],
                    'featured'       => (bool) $r['featured'],
                    'views'          => $r['views'] ?? 0,
                    'published_at'   => $r['published_at'],
                    'created_at'     => $r['created_at'],
                    'updated_at'     => $r['updated_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importGallery(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM gallery')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('gallery')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'title'       => $r['title'],
                    'description' => $r['description'],
                    'image_path'  => $r['image_path'],
                    'category'    => $r['category'],
                    'sort_order'  => $r['sort_order'] ?? 0,
                    'status'      => $r['status'],
                    'views'       => 0,
                    'created_at'  => $r['created_at'],
                    'updated_at'  => $r['created_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importEvents(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM events')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('events')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'title'                 => $r['title'],
                    'description'           => $r['description'],
                    'event_date'            => $r['event_date'],
                    'start_time'            => $r['start_time'],
                    'end_time'              => $r['end_time'],
                    'location'              => $r['location'],
                    'category'              => $r['category'],
                    'featured_image'        => $r['featured_image'],
                    'status'                => $r['status'],
                    'registration_required' => (bool) $r['registration_required'],
                    'max_participants'      => $r['max_participants'],
                    'created_by'            => $r['created_by'] ?: null,
                    'created_at'            => $r['created_at'],
                    'updated_at'            => $r['updated_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importAlumni(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM alumni')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('alumni')->updateOrInsert(
                ['email' => $r['email']],
                [
                    'full_name'          => $r['full_name'],
                    'phone'              => $r['phone'],
                    'graduation_year'    => $r['graduation_year'],
                    'course_studied'     => $r['course_studied'],
                    'current_profession' => $r['current_profession'],
                    'current_employer'   => $r['current_employer'],
                    'location'           => $r['location'],
                    'linkedin_profile'   => $r['linkedin_profile'],
                    'bio'                => $r['bio'],
                    'profile_photo'      => $r['profile_photo'],
                    'achievements'       => $r['achievements'],
                    'willing_to_mentor'  => (bool) $r['willing_to_mentor'],
                    'status'             => $r['status'],
                    'created_at'         => $r['created_at'],
                    'updated_at'         => $r['updated_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importAdmissions(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM admissions')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('admissions')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'student_name'         => $r['student_name'],
                    'parent_name'          => $r['parent_name'],
                    'email'                => $r['email'],
                    'phone'                => $r['phone'],
                    'address'              => $r['address'],
                    'date_of_birth'        => $r['date_of_birth'],
                    'gender'               => $r['gender'],
                    'previous_school'      => $r['previous_school'],
                    'class_applying'       => $r['class_applying'],
                    'documents_path'       => $r['documents_path'],
                    'application_fee_paid' => (bool) $r['application_fee_paid'],
                    'status'               => $r['status'],
                    'notes'                => $r['notes'],
                    'processed_by'         => $r['processed_by'] ?: null,
                    'processed_at'         => $r['processed_at'],
                    'created_at'           => $r['created_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importContactMessages(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM contact_messages')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('contact_messages')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'name'       => $r['name'],
                    'email'      => $r['email'],
                    'phone'      => $r['phone'],
                    'subject'    => $r['subject'],
                    'message'    => $r['message'],
                    'status'     => $r['status'],
                    'created_at' => $r['created_at'],
                ]
            );
            $n++;
        }
        return $n;
    }

    private function importActivityLog(PDO $legacy): int
    {
        $rows = $legacy->query('SELECT * FROM activity_log')->fetchAll();
        $n = 0;
        foreach ($rows as $r) {
            DB::table('activity_log')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'user_id'     => $r['user_id'] ?: null,
                    'action'      => $r['action'],
                    'description' => $r['description'],
                    'ip_address'  => $r['ip_address'],
                    'created_at'  => $r['created_at'],
                ]
            );
            $n++;
        }
        return $n;
    }
}
