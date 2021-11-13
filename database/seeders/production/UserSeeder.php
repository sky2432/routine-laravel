<?php

namespace Database\Seeders\production;

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
            'email' => config('const.GUEST_EMAIL'),
        ]);

        $routines = Routine::all();
        foreach ($routines as $routine) {
            CountService::updateRoutineCountData($routine->id);
            RankService::checkAllRank($routine->id);
        }
    }
}
