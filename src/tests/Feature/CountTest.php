<?php

namespace Tests\Feature;

use App\Models\Record;
use App\Models\Routine;
use App\Models\User;
use App\Services\CountService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\RankSeeder;
use Tests\TestCase;

class CountTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_done_days()
    {
        $this->seed(RankSeeder::class);

        $user = User::factory()->create([
            'name' => 'そら',
            'email' => 'user1@test.com',
            'password' => 1234
        ]);

        $date = new Carbon('-9 days');

        $routine = Routine::factory()->create([
            'user_id' => $user->id,
            'created_at' => $date->startOfDay(),
            'updated_at' => $date->startOfDay(),
        ]);

        $days = ['-1 days', '-2days','-4days', '-5days', '-6 days', '-8 days', '-9 days'];

        foreach ($days as $day) {
            Record::factory()->create([
                'routine_id' => $routine->id,
                'created_at' => new Carbon($day),
                'updated_at' => new Carbon($day)
            ]);
        }

        $done_days = CountService::getDoneDays($routine->id);

        $this->assertCount(10, $done_days);

        $expect = [
            Carbon::today()->subDays(9)->format('Y-m-d') => 1,
            Carbon::today()->subDays(8)->format('Y-m-d') => 1,
            Carbon::today()->subDays(7)->format('Y-m-d') => 0,
            Carbon::today()->subDays(6)->format('Y-m-d') => 1,
            Carbon::today()->subDays(5)->format('Y-m-d') => 1,
            Carbon::today()->subDays(4)->format('Y-m-d') => 1,
            Carbon::today()->subDays(3)->format('Y-m-d') => 0,
            Carbon::today()->subDays(2)->format('Y-m-d') => 1,
            Carbon::today()->subDay()->format('Y-m-d') => 1,
            Carbon::today()->format('Y-m-d') => 0,
        ];

        $this->assertEquals($expect, $done_days);
    }
}
