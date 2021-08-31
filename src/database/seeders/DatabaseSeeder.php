<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            $this->call([
                RankSeeder::class,
                local\UserSeeder::class,
            ]);
        } elseif (App::environment('production')) {
            $this->call([
                RankSeeder::class,
                production\UserSeeder::class,
            ]);
        }
    }
}
