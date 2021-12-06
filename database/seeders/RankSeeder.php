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
                'name' => config('const.ranks')[0],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[1],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[2],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[3],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[4],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[5],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[6],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => config('const.ranks')[7],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
