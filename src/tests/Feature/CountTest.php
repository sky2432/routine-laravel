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

    protected function setUp(): Void
    {
        parent::setUp();

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
    }

    public function test_get_done_days()
    {
        $routine = Routine::first();
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

    public function test_count_all_days()
    {
        $routine = Routine::first();
        $done_days = CountService::getDoneDays($routine->id);

        $all_days =CountService::countAllDays($done_days);

        $this->assertEquals(7, $all_days);
    }

    public function test_count_continuous_days()
    {
        $routine = Routine::first();
        $done_days = CountService::getDoneDays($routine->id);

        $continuous_days =CountService::countContinuousDays($done_days);

        $this->assertEquals([2, 3], $continuous_days);
    }

    public function test_count_recovery()
    {
        $routine = Routine::first();
        $done_days = CountService::getDoneDays($routine->id);

        $recovery_count =CountService::countRecovery($done_days);

        $this->assertEquals(2, $recovery_count);
    }
}
