<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ksrpmiunp.or.id'],
            [
                'name' => 'Admin',
                'password' => Hash::make('humasmedia'),
                'role' => 'Super Admin',
                'nia' => null,
            ]
        );
    }
}
