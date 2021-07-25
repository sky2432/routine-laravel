<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecoveryRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('recovery_ranks')->insert([
            [
                'name' => '見習い',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '復活',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '不屈',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '蘇生',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '転生',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '不死',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
