<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (config('app.env') === "local") {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->call([
                VaccineCenterSeeder::class,
            ]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
