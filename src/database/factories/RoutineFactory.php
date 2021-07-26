<?php

namespace Database\Factories;

use App\Models\Rank;
use App\Models\Record;
use App\Models\RecoveryRank;
use App\Models\Routine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class RoutineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Routine::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $routines = [
            'プログラミング',
            '読書',
            '筋トレ',
            '瞑想',
            'ヨガ',
            '早起き',
            '散歩'
        ];

        $rank_id = Rank::where('name', '見習い')->value('id');
        $recovery_rank_id = RecoveryRank::where('name', '見習い')->value('id');

        $date = new Carbon('-8 days');

        return [
            'name' => Arr::random($routines),
            'user_id' => User::pluck('id')->random(),
            'total_rank_id' => $rank_id,
            'continuous_rank_id' => $rank_id,
            'recovery_rank_id' => $recovery_rank_id,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Routine $routine) {
            $routine_array = $routine->toArray();
            $query['name'] = $routine_array['name'];
            $query['user_id'] = $routine_array['user_id'];

            $response = Routine::firstOrCreate(
                $query,
                $routine_array,
            );

            if ($response->wasRecentlyCreated) {
                Record::factory(10)->make([
                    'routine_id' => $response->id
                ]);
            }
        });
    }
}
