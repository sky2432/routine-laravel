<?php

namespace Database\Seeders\local;

use App\Models\Routine;
use App\Models\User;
use App\Services\CountService;
use App\Services\RankService;
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
        User::factory()->after()->create([
            'name' => 'ゲスト',
            'email' => config('const.guest_email'),
        ]);

        User::factory()->after()->create([
            'name' => 'そら',
            'email' => 'user1@test.com',
        ]);

        User::factory()->after()->create([
            'email' => 'user2@test.com',
        ]);

        $routines = Routine::all();
        foreach ($routines as $routine) {
            CountService::updateRoutineCountData($routine->id);
            RankService::checkAllRank($routine->id);
        }
    }
}
