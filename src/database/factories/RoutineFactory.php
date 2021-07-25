<?php

namespace Database\Factories;

use App\Models\Routine;
use App\Models\User;
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

        return [
            'name' => Arr::random($routines),
            'user_id' => User::pluck('id')->random(),
            'created_at' => $this->faker->dateTimeBetween('-1month', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1month', 'now'),
        ];
    }
}
