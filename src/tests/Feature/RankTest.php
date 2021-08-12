<?php

namespace Tests\Feature;

use App\Models\Routine;
use App\Models\User;
use App\Services\CountService;
use App\Services\RankService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\RankSeeder;
use Tests\TestCase;

class RankTest extends TestCase
{
    use RefreshDatabase;

    public function test_count_rank()
    {
        $this->seed(RankSeeder::class);

        $user = User::factory()->create();

        $rank_ids = RankService::getRankIds();

        $rank_array = [
            ['F', 'E', 'D'],
            ['C', 'B', 'A'],
            ['S', 'SS', 'F'],
            ['E', 'D', 'C'],
        ];

        foreach ($rank_array as $rank) {
            Routine::factory()->create([
            'user_id' => $user->id,
            'total_rank_id' => $rank_ids[$rank[0]],
            'highest_continuous_rank_id' => $rank_ids[$rank[1]],
            'recovery_rank_id' => $rank_ids[$rank[2]],
        ]);
        }

        $rank_count = CountService::countRank($user->id);

        $expect = [
            ['name' => 'SS', 'count' => 1],
            ['name' => 'S', 'count' => 1],
            ['name' => 'A', 'count' => 1],
            ['name' => 'B', 'count' => 1],
            ['name' => 'C', 'count' => 2],
            ['name' => 'D', 'count' => 2],
            ['name' => 'E', 'count' => 2],
            ['name' => 'F', 'count' => 2],
        ];

        $this->assertEquals($expect, $rank_count);
    }

    public function test_check_total_days_rank()
    {
        $this->seed(RankSeeder::class);

        $user = User::factory()->create();

        $routine = Routine::factory()->create([
            'user_id' => $user->id,
            'total_days' => 90
        ]);

        $rank_up_data = RankService::checkTotalDaysRank($routine->id);

        $new_routine = Routine::find($routine->id);
        $rank_ids = RankService::getRankIds();

        $this->assertEquals($rank_ids['A'], $new_routine->total_rank_id);

        $expect = [
            'name' => '累計日数',
            'rank_name' => 'A'
        ];

        $this->assertEquals($expect, $rank_up_data);
    }
}
