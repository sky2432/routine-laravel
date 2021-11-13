<?php

namespace Tests\Feature;

use App\Models\Record;
use App\Models\Routine;
use App\Models\User;
use App\Services\RecordService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\RankSeeder;
use Tests\TestCase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    protected $user_id;
    protected $routine_id;

    protected function setUp(): Void
    {
        parent::setUp();

        $this->seed(RankSeeder::class);

        $user = User::factory()->create();
        $this->user_id = $user->id;

        $routine = Routine::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->routine_id = $routine->id;
    }

    public function test_insert_today_record()
    {
        $record = Record::factory()->create([
            'routine_id' => $this->routine_id,
            'created_at' => new Carbon(),
            'updated_at' => new Carbon()
        ]);

        $items = Routine::WithChildTable()->where('user_id', $this->user_id)->where('is_archive', false)->get();

        $routines = RecordService::insertTodayRecord($items);

        $this->assertSame($record->id, $routines[0]['today_record']['id']);
    }

    public function test_no_insert_today_record()
    {
        Record::factory()->create([
            'routine_id' => $this->routine_id,
            'created_at' => Carbon::yesterday(),
            'updated_at' => Carbon::yesterday()
        ]);

        $items = Routine::WithChildTable()->where('user_id', $this->user_id)->where('is_archive', false)->get();

        $routines = RecordService::insertTodayRecord($items);

        $this->assertSame(null, $routines[0]['today_record']);
    }
}
