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

        $user = User::factory()->create([
            'name' => 'そら',
            'email' => 'user1@test.com',
            'password' => 1234
        ]);

        $rankIds = RankService::getRankIds();

        $rank_array = [
            ['F', 'E', 'D'],
            ['C', 'B', 'A'],
            ['S', 'SS', 'F'],
            ['E', 'D', 'C'],
        ];

        foreach ($rank_array as $rank) {
            Routine::factory()->create([
            'user_id' => $user->id,
            'total_rank_id' => $rankIds[$rank[0]],
            'highest_continuous_rank_id' => $rankIds[$rank[1]],
            'recovery_rank_id' => $rankIds[$rank[2]],
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
}
