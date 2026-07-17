<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * The default scaffold seeder called User::factory() with 'name'/'email'
     * — fields this project's `user` table has never had (it's user_name/
     * email_user/full_name), so `--seed` would fail on a fresh install
     * rather than leave it usable. Seeds one real admin account instead,
     * matching README's setup instructions.
     */
    public function run(): void
    {
        if (User::where('role', 1)->exists()) {
            return;
        }

        User::create([
            'user_name' => 'admin',
            'password' => Hash::make('password'),
            'full_name' => 'Turbotech Admin',
            'sex' => 0,
            'email_user' => 'admin@example.com',
            'address' => '',
            'phone_user' => '0000000000',
            'img_user' => '',
            'role' => 1,
        ]);
    }
}
