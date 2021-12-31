<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToDoItemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('to_do_item_user')->updateOrInsert([
            'to_do_item_id' => 1,
            'user_id' => 1,
        ]);

        DB::table('to_do_item_user')->updateOrInsert([
            'to_do_item_id' => 2,
            'user_id' => 1,
        ]);

        DB::table('to_do_item_user')->updateOrInsert([
            'to_do_item_id' => 3,
            'user_id' => 2,
        ]);
    }
}
