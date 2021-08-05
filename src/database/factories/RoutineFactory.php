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
            '散歩',
            '断食',
            'ジョギング',
            '日記'
        ];

        $rank_id = Rank::DefaultId();
        $recovery_rank_id = RecoveryRank::DefaultId();

        $created_at = new Carbon('-7 month');

        return [
            'name' => Arr::random($routines),
            'user_id' => User::pluck('id')->random(),
            'total_rank_id' => $rank_id,
            'highest_continuous_rank_id' => $rank_id,
            'recovery_rank_id' => $recovery_rank_id,
            'created_at' => $created_at,
            'updated_at' => $created_at,
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
                $num = $this->faker->randomElement([14, 30, 60, 90, 120, 150, 210, 365]);

                Record::factory($num)->make([
                    'routine_id' => $response->id
                ]);
            }
        });
    }
}
