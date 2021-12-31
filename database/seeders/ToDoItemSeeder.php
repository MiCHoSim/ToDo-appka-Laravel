<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToDoItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('to_do_items')->updateOrInsert([
            'autor_id' => 1,
            'task' => 'Vytvoriť ToDo App',
            'term' => Carbon::create('2021', '12', '31'),
            'category_id' => 2,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('to_do_items')->updateOrInsert([
            'autor_id' => 1,
            'task' => 'Skialp',
            'term' => Carbon::create('2021', '12', '29','09'),
            'category_id' => 1,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('to_do_items')->updateOrInsert([
            'autor_id' => 2,
            'task' => 'Nakupiť',
            'category_id' => 3,
            'done' => 1,
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
