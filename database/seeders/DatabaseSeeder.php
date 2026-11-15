<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'admin', 'editor'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        $email = env('ADMIN_EMAIL', 'admin@makererecompetenthighschool.com');
        $password = env('ADMIN_PASSWORD', 'ChangeMe@2026');

        $admin = User::where('username', 'admin')->first();

        if ($admin) {
            $admin->update([
                'name'     => $admin->name ?: 'Site Administrator',
                'email'    => $admin->email ?: $email,
                'password' => Hash::make($password),
                'role'     => 'super_admin',
                'status'   => 'active',
            ]);
        } else {
            $admin = User::create([
                'username' => 'admin',
                'name'     => 'Site Administrator',
                'email'    => $email,
                'password' => Hash::make($password),
                'role'     => 'super_admin',
                'status'   => 'active',
            ]);
        }

        $admin->syncRoles(['super_admin']);
    }
}
