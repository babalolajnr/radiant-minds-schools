<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Abdulqudduus',
            'last_name' => 'Babalola',
            'user_type' => 'master',
            'status' => 'active',
            'is_verified' => true,
            'email' => 'babalolajnr@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('11111111'), // 8 ones
            'remember_token' => Str::random(10),
        ]);
    }
}
