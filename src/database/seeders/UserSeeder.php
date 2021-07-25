<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'ゲスト',
            'email' => config('const.GUEST_EMAIL'),
        ]);

        $user1 = User::factory()->create([
            'name' => 'そら',
            'email' => 'user1@test.com',
        ]);

        $user2 = User::factory()->create([
            'email' => 'user2@test.com',
        ]);
    }
}
