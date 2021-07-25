<?php

namespace Database\Factories;

use App\Models\Record;
use App\Models\Routine;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Record::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'routine_id' => Routine::pluck('id')->random(),
        ];
    }
}
