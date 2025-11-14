<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin if not exists
        User::firstOrCreate(
            ['email' => 'admin@dariabeauty.ro'],
            [
                'name' => 'Super Admin',
                'password' => 'secret1234', // hashed by model cast
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );
    }
}
