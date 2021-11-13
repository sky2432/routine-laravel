<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ranks')->insert([
            [
                'name' => config('const.RANK')[0],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[1],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[2],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[3],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[4],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[5],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[6],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.RANK')[7],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
