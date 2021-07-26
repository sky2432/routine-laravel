<?php

namespace Database\Factories;

use App\Models\Record;
use App\Models\Routine;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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
        $date = $this->faker->dateTimeBetween('-2week', 'now');

        return [
            'routine_id' => Routine::pluck('id')->random(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Record $record) {
            $date_time = $record->created_at;
            $date = substr($date_time, 0, 10);

            $response = Record::where('routine_id', $record->routine_id)->where('created_at', 'like', "$date%")->first();

            $record_array = $record->toArray();
            $time = strtotime($record->created_at);
            $date_time = date('Y-m-d H:i:s', $time);
            $record_array['created_at'] = $date_time;
            $record_array['updated_at'] = $date_time;

            if ($response) {
                return;
            } else {
                DB::table('records')->insert($record_array);
            }
        });
    }
}
