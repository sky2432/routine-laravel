<?php

namespace Database\Factories;

use App\Models\Routine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('1234'),
            'image_url' => config('const.DEFAULT_IMAGE_URL'),
        ];
    }

    public function after()
    {
        return $this->afterCreating(function (User $user) {
            Routine::factory(10)->after()->make([
                'user_id' => $user->id
            ]);
        });
    }
}
