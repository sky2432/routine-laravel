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
                'name' => '見習い',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '初級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '中級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '上級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '聖級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '王級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '帝級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '神級',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
