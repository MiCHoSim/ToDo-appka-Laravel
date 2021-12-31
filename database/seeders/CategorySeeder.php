<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Vygeneruj Kategórie To Do úloh.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('categories')->updateOrInsert([
            'name' => 'Šport',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('categories')->updateOrInsert([
            'name' => 'Práca',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('categories')->updateOrInsert([
            'name' => 'Nakupovanie',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
