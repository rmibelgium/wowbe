<?php

namespace Database\Seeders;

use App\Models\Reading;
use App\Models\Site;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Site::factory(10)->create();
        Reading::factory(100)->create();
    }
}
